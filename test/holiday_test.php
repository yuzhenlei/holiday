<?php
namespace Holiday;

foreach (glob('./*.php') as $file) {
    require_once $file;
}

if (count($argv) != 2) {
    exit('usage: php holiday.php [year]'.PHP_EOL);
}
$year = intval($argv[1]);
$currentYear = date('Y');
for (; $year <= $currentYear; $year++) {
    $match = new Match($year);
    $searchUrl = "http://sousuo.gov.cn/s.htm?t=paper&advance=false&n=10&timetype=&mintime=&maxtime=&sort=pubtime&sortType=0&q=";
    $searchUrl .= urlencode("国务院办公厅关于{$year}年部分节假日安排的通知");
    $searchPageContent = file_get_contents($searchUrl);
    $announcementUrl = $match->url($searchPageContent);
    $announcementPageContent = file_get_contents($announcementUrl);
    $announcementContent = $match->announcement($announcementPageContent);

    $match->holiday($announcementContent);

    /*var_dump($match->getHolidayDate());
    var_dump($match->getAdjustDate());*/

    if (empty($match->getHolidayDate()) || empty($match->getAdjustDate())) {
        echo "$year: failed".PHP_EOL;
    } else {
        echo "$year: ok".PHP_EOL;
    }
}

