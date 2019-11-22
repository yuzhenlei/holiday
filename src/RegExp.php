<?php
namespace Holiday;

class RegExp
{
    public static function parseUrl($keyword)
    {
        return '/<\S+\s+.*?class="res-sub-title".*?>.*?<a\s+.*?href="(.*?)".*?>.*?'.$keyword.'.*?<\/a>.*?<\/\S+>/';
    }

    public static function parseAnnouncement()
    {
        return '/<\S+\s+class="b12c"[\S\s]*?>([\s\S]*?)<\/td>/';
    }

    public static function holidayRangeT1()
    {
        return '/(\d{4}+)年(\d{1,2}+)月(\d{1,2}+)日[至—]+(\d{4}+)年(\d{1,2}+)月(\d{1,2}+)日[放假|调休]+/';
    }

    public static function holidayRangeT2()
    {
        return '/[^年](\d{1,2}+)月(\d{1,2}+)日[至—]+[^年](\d{1,2}+)月(\d{1,2}+)日[放假|调休]+/';
    }

    public static function holidayRangeT3()
    {
        return '/[^年](\d{1,2}+)月(\d{1,2}+)日[至—]+(\d{1,2}+)日[放假|调休]+/';
    }

    public static function holidayRangeT4()
    {
        //return '/[^年](\d{1,2}+)月(\d{1,2}+)日[放假|调休]+/';
        return '/[^至—]*?((\d{4})+年)*(\d{1,2}+)月(\d{1,2}+)日[放假|调休]+/';
    }

    public static function holidayRangeT5()
    {
        return '/[^年](\d{1,2}+)月(\d{1,2}+)日放假，[^年](\d{1,2}+)月(\d{1,2}+)日.*?补休/';
    }

    public static function adjustDateT1()
    {
        return '/。(\d{4}+)年(\d{1,2}+)月(\d{1,2}+)日[^、]*?上班/';
    }

    public static function adjustDateT2()
    {
        return '/。(\d{4}+)年(\d{1,2}+)月(\d{1,2}+)日.*?、(\d{4}+)年(\d{1,2}+)月(\d{1,2}+)日.*?上班/';
    }

    public static function adjustDateT3()
    {
        return '/。(\d{1,2}+)月(\d{1,2}+)日[^、]*?上班/';
    }

    public static function adjustDateT4()
    {
        return '/。(\d{1,2}+)月(\d{1,2}+)日.*?、(\d{1,2}+)月(\d{1,2}+)日.*?上班/';
    }

    public static function adjustDateT5()
    {
        return '/。(\d{1,2}+)月(\d{1,2}+)日.*?、(\d{1,2}+)日.*?上班/';
    }
}
