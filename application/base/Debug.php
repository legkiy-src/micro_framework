<?php

class Debug
{
    private static $startTime = 0;
    private static $endTime = 0;
    private static $resultTime = 0;

    public static function setStartTime($time)
    {
        self::$startTime = $time;
    }

    public static function getStartTime()
    {
        return self::$startTime;
    }

    public static function setEndTime($time)
    {
        self::$endTime = $time;
    }

    public static function getEndTime()
    {
        return self::$endTime;
    }

    public static function getResultTime()
    {
        self::$resultTime = self::$endTime - self::$startTime;

        return [
            'runtime' => [
                'startTime' => self::$startTime,
                'endTime' => self::$endTime,
                'resultTime' => self::$resultTime
            ]
        ];
    }
}
