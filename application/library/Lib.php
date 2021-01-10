<?php

trait Lib
{
    public function debugText($data = '', $fileName = 'data.txt')
    {
        $f = fopen(DIR_LOG_APP . $fileName, 'a');
        fwrite($f, date('d-m-Y H:i:s') . "\r\n" . $data . "\r\n\r\n");
        fclose($f);
    }

    public function debugArray($data = [], $fileName = 'data.txt')
    {
        $f = fopen(DIR_LOG_APP . $fileName, 'a');
        fwrite($f, date('d-m-Y H:i:s') . "\r\n" . json_encode($data) . "\r\n\r\n");
        fclose($f);
    }

    public function writeErrorLog($data = [], $fileName = 'sql_error.log', $title = '')
    {
        $dataSend = json_encode($data);
        $f = fopen(DIR_LOG_APP . $fileName, 'a');
        fwrite($f, date('d-m-Y H:i:s') . " $title" . "\r\n" . $dataSend . "\r\n\r\n");
        fclose($f);
    }

    public function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4
                ) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);

            return strtolower($uuid);
        }
    }

    // метод получает количество месяцев и возвращает массив дат в периоде текущая дата - $numMounths,
    // с интервалом $numDays
    public function getDatesArray($numMonths, $numDays, $dateStartParam = false, $dateEndParam = false)
    {
        $result = [];

        if (!$dateStartParam) {
            $startPoint = new DateTime(date('Y-m-d 00:00:00'));
            $startPoint->modify("-{$numMonths} month");
        } else {
            $startPoint = new DateTime($dateStartParam);
        }

        $dateStart = $startPoint->format('Y-m-d H:i:s');

        if (!$dateEndParam) {

            $dateCompareObj = new DateTime(date('Y-m-d H:i:00'));
            //$dateCompareObj->modify('+1 day');
            $dateCompare = $dateCompareObj->format('Y-m-d H:i:s');

        } else {
            $dateCompare = $dateEndParam;
        }

        while (strtotime($dateStart) <= strtotime($dateCompare)) {

            $startObj = new DateTime($dateStart);
            $dateEndObj = new DateTime($startObj->format('Y-m-d H:i:s'));
            $dateEndObj->modify("+{$numDays} day");
            $dateEnd = $dateEndObj->format('Y-m-d H:i:s');

            if (strtotime($dateEnd) > strtotime($dateCompare)) {
                $dateEnd = $dateCompare;
            }

            $result[] = [
                'dateStart' => $dateStart,
                'dateEnd' => $dateEnd,
            ];

            $dateStart = $dateEnd;

            if (strtotime($dateStart) >= strtotime($dateCompare)) {
                break;
            }
        }

        return $result;
    }

    public function implodeAssoc($glue, $arr)
    {
        $result = '';

        foreach ($arr as $val) {

            $values = array_values($val);

            foreach ($values as $valueItem) {

                if(!$result) {
                    $result .= $valueItem;
                } else {
                    $result .= $glue . $valueItem;
                }
            }
        }

        return $result;
    }

    public function getStartCurrentQuarter()
    {
        if ((strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-01-01 00:00:00')))
           && (strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-04-01 00:00:00')))) {
            return date('Y-01-01 00:00:00');
        }

        if ((strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-04-01 00:00:00')))
            && (strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-07-01 00:00:00')))) {
            return date('Y-04-01 00:00:00');
        }

        if ((strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-07-01 00:00:00')))
            && (strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-10-01 00:00:00')))) {
            return date('Y-07-01 00:00:00');
        }

        if ((strtotime(date('Y-m-d H:i:s')) >= strtotime(date('Y-10-01 00:00:00')))
            && (strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-12-31 23:59:59')))) {
            return date('Y-10-01 00:00:00');
        }
    }



    public function checkWorkTime()
    {
        $applicationRegistry = ApplicationRegistry::instance();
        $time = $applicationRegistry->get('startTimeImport');

        if (strtotime(date('Y-m-d H:i:s')) - strtotime($time) > 1500) {
            return false;
        }

        return true;
    }

    public function getParamFromData($data, $name, $type)
    {
        $result = false;

        switch ($type) {
            // если тип строка
            case 1:
                $result = (isset($data[$name]) && $data[$name] !== '') ? trim($data[$name]) : null;
                break;
            // если дата в формате ISO
            case 2:
                $result = (isset($data[$name]) && $data[$name] !== '') ? str_replace('T', ' ', trim($data[$name])) : null;
                break;
            // если это сумма в копейках
            case 3:
                $result = (isset($data[$name])  && $data[$name] !== '') ? abs($data[$name]) / 100 : null;
                break;
            // если это число
            case 4:
                $result = (isset($data[$name])  && $data[$name] !== '') ? abs($data[$name]) : 0;
                break;
            // если это число, по умолчанию null
            case 5:
                $result = (isset($data[$name])  && $data[$name] !== '') ? abs($data[$name]) : null;
                break;
        }

        return $result;
    }
    public function maxDate($date1, $date2)
    {
        if (strtotime($date1) > strtotime($date2)) {
            return $date1;
        }

        return $date2;
    }

    public function minDate($date1, $date2)
    {
        if (strtotime($date1) < strtotime($date2)) {
            return $date1;
        }

        return $date2;
    }

    public function getBeginningDay($date)
    {
        $dateObj = new DateTime($date);

        return $dateObj->format('Y-m-d') . ' 00:00:00';
    }

    public function getEndDay($date)
    {
        $dateObj = new DateTime($date);

        return $dateObj->format('Y-m-d') . ' 23:59:59';
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function hashPasswordUsers($passw) {

        $prom[1] = md5($passw);
        $prom[2][1] = abs(ord($prom[1][0]));
        $prom[2][2] = abs(ord($prom[1][3]) + $prom[2][1]);
        $prom[2][3] = abs(ord($prom[1][7]) + $prom[2][1] - $prom[2][2]);
        $prom[2][4] = abs(ord($prom[1][15]) + $prom[2][1] - $prom[2][2] + $prom[2][1]);
        $prom[2][5] = abs(ord($prom[1][31]) + $prom[2][1] - $prom[2][2] + $prom[2][3] - $prom[2][4]);

        $prom[3] = '0'.chr($prom[2][1]).chr($prom[2][2]).chr($prom[2][3]).chr($prom[2][4]).chr($prom[2][5]);

        $result = '{md5}'.(md5($prom[1].$prom[3]));

        return $result;

        //return md5($passw);

    }

    public function compareSign($sign1, $sign2)
    {
        if (strcasecmp($sign1, $sign2) != 0) {
            return false;
        }

        return true;
    }

    public function generateName($len = 8)
    {
        $chars = 'abcdefghijklmnoprstuqwvxyzABCDEFGHIJKLMNOPQRSTUWVXYZ1234567890';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $len; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    function isJson($string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}
