<?php

class CurlRequest
{
    public $errorCode;
    public $errorMessage;
    public $curlInfo;
    public $result;

    public function request($httpMethod, $url, $data = [], $headers = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (30));
        curl_setopt($ch, CURLOPT_TIMEOUT, (30));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (count($headers)) {

            $headersAdd = [];

            foreach ($headers as $key => $value) {
                $headersAdd[] = "$key: $value";
            }
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headersAdd);
        }

        // если это GET запрос
        if ($httpMethod === 0) {

            if ($data) {
                $url .= '?' . http_build_query($data);
            }

            curl_setopt($ch, CURLOPT_URL, $url);
        }

        // если это POST запрос
        if ($httpMethod === 1) {

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, true);
        }

        $response = curl_exec($ch);
        $this->curlInfo = curl_getinfo($ch);
        $this->errorCode = curl_errno($ch);
        $this->errorMessage = curl_error($ch);
        curl_close($ch);

        if ($this->errorCode !== 0) {
            return false;
        }

        return $response;
    }
}
