<?php
namespace Holiday;

class DateUtil
{
    public static function parseRange($lowerDate, $upperDate)
    {
        $result = [];
        $upperTimestamp = strtotime($upperDate);

        for ($i = strtotime($lowerDate); $i <= $upperTimestamp; $i = strtotime('+1day', $i)) {
            $result[] = date('Y-m-d', $i);
        }
        return $result;
    }
}