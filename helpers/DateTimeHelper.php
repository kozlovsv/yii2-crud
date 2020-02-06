<?php

namespace kozlovsv\crud\helpers;

use Yii;
use DateTime;
use DateInterval;
use yii\base\InvalidConfigException;

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
     * @throws InvalidConfigException
     */
    public static function convertBySave($time, $format = null)
    {
        if ($time == null) {
            return null;
        }
        return Yii::$app->formatter->asDate($time, $format === null ? self::SAVE_FORMAT : $format);
    }

    /**
     * Прибавить к дате $time значение $value
     * @param datetime:string $time
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
     * @param datetime:string $time
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
     * Получить текущее время в MySql формате
     * @param string $format
     * @return string
     */
    public static function now($format = 'Y-m-d H:i:s')
    {
        $datetime = new DateTime();

        return $datetime->format($format);
    }
}