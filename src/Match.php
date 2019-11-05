<?php
namespace Holiday;

class Match
{
    private $year = null;

    private $holiday = [];

    private $adjustWeekend = [];

    public function __construct($year = null)
    {
        $this->year = $year ?: date('Y');
    }

    public function url($content)
    {
        $keyword = "国务院办公厅关于{$this->year}年部分节假日安排的通知";
        $regexp = RegExp::parseUrl($keyword);
        $matches = [];
        preg_match($regexp, $content, $matches);
        return $matches[1];
    }

    public function announcement($content)
    {
        $regexp = RegExp::parseAnnouncement();
        $matches = [];
        preg_match($regexp, $content, $matches);
        return $matches[1];
    }

    public function holiday($content)
    {
        $lines = preg_split("/[\r\n]|<br\/*>/", $content);
        foreach ($lines as $line) {
            if (!$line) {
                continue;
            }
            if ($line) {
                $arrDate = $this->parseDateRange($line);
                $this->addHoliday($arrDate);
                $arrAdjustDate = $this->parseAdjustRange($line);
                $this->addAdjustWeekend($arrAdjustDate);
            }
        }
    }

    private function parseDateRange($content)
    {
        $matches = [];
        if (preg_match(RegExp::holidayRangeT1(), $content, $matches)) {
            return $this->arrangeDateForT1($matches);
        }
        if (preg_match(RegExp::holidayRangeT2(), $content, $matches)) {
            return $this->arrangeDateForT2($matches);
        }
        if (preg_match(RegExp::holidayRangeT3(), $content, $matches)) {
            return $this->arrangeDateForT3($matches);
        }
        if (preg_match(RegExp::holidayRangeT4(), $content, $matches)) {
            return $this->arrangeDateForT4($matches);
        }
        if (preg_match(RegExp::holidayRangeT5(), $content, $matches)) {
            return $this->arrangeDateForT5($matches);
        }
        return false;
    }

    private function arrangeDateForT1($matches)
    {
        array_shift($matches);
        list($y1, $m1, $d1, $y2, $m2, $d2) = $matches;
        return DateUtil::parseRange("$y1-$m1-$d1", "$y2-$m2-$d2");
    }

    private function arrangeDateForT2($matches)
    {
        array_shift($matches);
        list($m1, $d1, $m2, $d2) = $matches;
        return DateUtil::parseRange("{$this->year}-$m1-$d1", "{$this->year}-$m2-$d2");
    }

    private function arrangeDateForT3($matches)
    {
        array_shift($matches);
        list($m1, $d1, $d2) = $matches;
        return DateUtil::parseRange("{$this->year}-$m1-$d1", "{$this->year}-$m1-$d2");
    }

    private function arrangeDateForT4($matches)
    {
        array_shift($matches);
        list($m1, $d1) = $matches;
        return ["{$this->year}-$m1-$d1"];
    }

    private function arrangeDateForT5($matches)
    {
        array_shift($matches);
        list($m1, $d1, $m2, $d2) = $matches;
        return ["{$this->year}-$m1-$d1", "{$this->year}-$m2-$d2"];
    }

    private function parseAdjustRange($content)
    {
        $matches = [];
        if (preg_match(RegExp::adjustDateT1(), $content, $matches)) {
            return $this->arrangeAdjustDateForT1($matches);
        }
        if (preg_match(RegExp::adjustDateT2(), $content, $matches)) {
            return $this->arrangeAdjustDateForT2($matches);
        }
        if (preg_match(RegExp::adjustDateT3(), $content, $matches)) {
            return $this->arrangeAdjustDateForT3($matches);
        }
        if (preg_match(RegExp::adjustDateT4(), $content, $matches)) {
            return $this->arrangeAdjustDateForT4($matches);
        }
        if (preg_match(RegExp::adjustDateT5(), $content, $matches)) {
            return $this->arrangeAdjustDateForT5($matches);
        }
        return false;
    }

    private function arrangeAdjustDateForT1($matches)
    {
        array_shift($matches);
        list($y1, $m1, $d1) = $matches;
        return ["$y1-$m1-$d1"];
    }

    private function arrangeAdjustDateForT2($matches)
    {
        array_shift($matches);
        list($y1, $m1, $d1, $y2, $m2, $d2) = $matches;
        return ["$y1-$m1-$d1", "$y2-$m2-$d2"];
    }

    private function arrangeAdjustDateForT3($matches)
    {
        array_shift($matches);
        list($m1, $d1) = $matches;
        return ["{$this->year}-$m1-$d1"];
    }

    private function arrangeAdjustDateForT4($matches)
    {
        array_shift($matches);
        list($m1, $d1, $m2, $d2) = $matches;
        return ["{$this->year}-$m1-$d1", "{$this->year}-$m2-$d2"];
    }

    private function arrangeAdjustDateForT5($matches)
    {
        array_shift($matches);
        list($m1, $d1, $d2) = $matches;
        return ["{$this->year}-$m1-$d1", "{$this->year}-$m1-$d2"];
    }

    private function addHoliday($date)
    {
        if (!is_array($date))
            return false;
        $this->holiday = array_merge($this->holiday, $date);
        array_unique($this->holiday);
        return true;
    }

    private function addAdjustWeekend($date)
    {
        if (!is_array($date))
            return false;
        $this->adjustWeekend = array_merge($this->adjustWeekend, $date);
        array_unique($this->adjustWeekend);
        return true;
    }

    public function getHolidayDate()
    {
        return $this->holiday;
    }

    public function getAdjustDate()
    {
        return $this->adjustWeekend;
    }
}
