<?php
namespace app\helpers;

class DatetimeHelper
{
    public static function isEmpty($str)
    {
        return empty($str) || $str == '0000-00-00 00:00:00';
    }

    public static function dateTimeDifference(string $dateTime1, string $dateTime2 = null)
    {
        $dateTime2 = $dateTime2 ?? DATETIME;

        return strtotime($dateTime1) - strtotime($dateTime2);
    }
}