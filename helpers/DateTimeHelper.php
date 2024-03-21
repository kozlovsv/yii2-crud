<?php

namespace kozlovsv\crud\helpers;

use Exception;
use Yii;
use DateTime;
use DateInterval;

/**
 * Хелпер работы со временем
 */
class DateTimeHelper
{
    /**
     * Формат для сохранения в бд
     */
    const SAVE_FORMAT = 'php:Y-m-d H:i:s';

    /**
     * Формат по умолчанию
     */
    const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    /**
     * Конвертирование для сохранения
     * @param string $time
     * @param string|null $format
     * @return string
     */
    public static function convertBySave($time, $format = null)
    {
        try {
            if (empty($time)) {
                return null;
            }
            return Yii::$app->formatter->asDate($time, $format === null ? self::SAVE_FORMAT : $format);
        } catch (Exception) {
            return null;
        }
    }

    /**
     * Прибавить к дате $time значение $value
     * @param datetime|string $time
     * @param string $value
     * @param string $format
     * @return string
     */
    public static function add($time, $value, $format = 'd.m.Y H:i')
    {
        $datetime = new DateTime($time);
        $datetime->add(new DateInterval($value));

        return $datetime->format($format);
    }

    /**
     * Отнять от даты $time значение $value
     * @param datetime|string $time
     * @param string $value
     * @param string $format
     * @return string
     */
    public static function sub($time, $value, $format = 'd.m.Y H:i')
    {
        $datetime = new DateTime($time);
        $datetime->sub(new DateInterval($value));

        return $datetime->format($format);
    }

    /**
     * @param string $time
     * @param int $hour
     * @param int $minute
     * @return string
     * @throws Exception
     */
    public static function setTime($time, $hour = 0, $minute = 0)
    {
        $datetime = new DateTime($time);
        $datetime->setTime($hour, $minute);

        return $datetime->format(self::DEFAULT_FORMAT);
    }

    /**
     * @param string $date_1
     * @param string $date_2
     * @return int
     */
    public static function compare($date_1, $date_2)
    {
        try {
            $date = new DateTime($date_1);
            return $date->diff(new DateTime($date_2))->invert;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Получить текущее время в MySql формате
     * @param string $format
     * @return string
     */
    public static function now($format = 'Y-m-d H:i:s')
    {
        $datetime = new DateTime();

        return $datetime->format($format);
    }

    /**
     * Получить разницу во времени
     * @param $fromDate
     * @param $toDate
     * @param string $format
     * @return string
     * @throws Exception
     */
    public static function interval($fromDate, $toDate, $format = '%R%a')
    {
        $fromDateTime = new DateTime($fromDate);
        $toDateTime = new DateTime($toDate);
        $interval = $fromDateTime->diff($toDateTime);

        return $interval->format($format);
    }

    /**
     * Получить дату начала текущей недели
     * @param string $format
     * @return string
     * @throws Exception
     */
    public static function beginWeek($format = 'Y-m-d')
    {
        return (new DateTime())->modify('Sunday')->modify('-6 days')->format($format);
    }

    /**
     * Получить дату окончания текущей недели
     * @param string $format
     * @return string
     * @throws Exception
     */
    public static function endWeek($format = 'Y-m-d')
    {
        return (new DateTime())->modify('Sunday')->format($format);
    }

    /**
     * @return array
     */
    public static function monthList()
    {
        return [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        ];
    }

    /**
     * @param string $minYear
     * @return array
     */
    public static function yearList($minYear = '2000')
    {
        $items = [];
        $current = date('Y');

        while ($current >= $minYear) {
            $items[$current] = $current;
            $current--;
        }

        return $items;
    }

    /**
     * @return array
     */
    public static function hourRangeList()
    {
        return [
            0 => '00:00 - 01:00',
            1 => '01:00 - 02:00',
            2 => '02:00 - 03:00',
            3 => '03:00 - 04:00',
            4 => '04:00 - 05:00',
            5 => '05:00 - 06:00',
            6 => '06:00 - 07:00',
            7 => '07:00 - 08:00',
            8 => '08:00 - 09:00',
            9 => '09:00 - 10:00',
            10 => '10:00 - 11:00',
            11 => '11:00 - 12:00',
            12 => '12:00 - 13:00',
            13 => '13:00 - 14:00',
            14 => '14:00 - 15:00',
            15 => '15:00 - 16:00',
            16 => '16:00 - 17:00',
            17 => '17:00 - 18:00',
            18 => '18:00 - 19:00',
            19 => '19:00 - 20:00',
            20 => '20:00 - 21:00',
            21 => '21:00 - 22:00',
            22 => '22:00 - 23:00',
            23 => '23:00 - 00:00',
        ];
    }

    /**
     * @param $number
     * @return string
     */
    public static function monthName($number)
    {
        $list = static::monthList();
        return $list[$number] ?? 'Не задано';
    }

    /**
     * Получить разницу во времени
     * @param string $begin
     * @param string|null $end
     * @return DateInterval
     * @throws Exception
     */
    public static function spendTime($begin, $end = null)
    {
        return (new DateTime($end))->diff(new DateTime($begin));
    }


    /**
     * correctly calculates end of months when we shift to a shorter or longer month
     * workaround for http://php.net/manual/en/datetime.add.php#example-2489
     *
     * Makes the assumption that shifting from the 28th Feb +1 month is 31st March
     * Makes the assumption that shifting from the 28th Feb -1 month is 31st Jan
     * Makes the assumption that shifting from the 29,30,31 Jan +1 month is 28th (or 29th) Feb
     *
     *
     * @param string $aDate
     * @param int $months positive or negative
     *
     * @return DateTime new instance - original parameter is unchanged
     * @throws Exception
     */
    public static function monthShifter($aDate, $months)
    {
        $aDate = new DateTime($aDate);
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if ($dateB != $dateA->modify($months * -1 . ' Month')) {
            $result = $plusMonths->modify('last day of last month');
        } elseif ($aDate == $dateB->modify('last day of this month')) {
            $result = $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }
}