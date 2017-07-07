<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/7
 * Time: 22:45
 */
// 创建DateTime实例
$datetime = new DateTime('2014-01-01 14:00:00');

// 创建长度为两周的间隔
$interval = new DateInterval('P2W');

// 修改DateTime类实例
$datetime->add($interval);
echo $datetime->format('Y-m-d H:i:s');

// 创建相对的DateInterval实例
$dateStart = new DateTime();
// 从相对时间的字符串设置一个DateInterval实例
$dateInterval = DateInterval::createFromDateString('-1 day');
// 创建一个在给定时间段内以时间段迭代的对象
$datePeriod = new DatePeriod($dateStart, $dateInterval, 3);
foreach ($datePeriod as $date) {
    echo $date->format('Y-m-d'), PHP_EOL;
}

$timeZone = new DateTimeZone('Asia/shanghai');
$datetime = new DateTime('2014-08-20', $timeZone);
$datetime->setTimezone(new DateTimeZone('America/New_York'));

// 使用DatePeriod类
$start = new DateTime();
$interval = new DateInterval('P2W');
$period = new DatePeriod($start, $interval, 3);

foreach ($period as $nextDateTime) {
    echo $nextDateTime->format('Y-m-d H:i:s'), PHP_EOL;
}

// 排除起始日期
$period = new DatePeriod($start, $interval, 3, DatePeriod::EXCLUDE_START_DATE);

foreach ($period as $nextDateTime) {
    echo $nextDateTime->format('Y-m-d H:i:s'), PHP_EOL;
}