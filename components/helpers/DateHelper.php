<?php

namespace app\components\helpers;

use DateTime;
use Yii;

class DateHelper
{
    const FORMAT_DATE_RANGE = 'd.m.Y - d.m.Y';
    const FORMAT_DATE = 'd.m.Y';
    const FORMAT_DATE_PHP = 'php: ' . self::FORMAT_DATE;
    const FORMAT_DATETIME = 'd.m.Y H:i:s';
    const FORMAT_DATETIME_PHP = 'php:' . self::FORMAT_DATETIME;

    const FORMAT_DB_DATE = 'Y-m-d';
    const FORMAT_DB_DATETIME = 'Y-m-d H:i:s';

    public static function getBetweenQuery($value, $field = 'created_at')
    {
        $value = explode(' - ', $value);
        if (count($value) != 2) {
            return '1=1'; // true query
        }
        return [
            'BETWEEN',
            $field,
            self::getBeginDay($value[0]),
            self::getEndDay($value[1]),
        ];
    }

    public static function getBeginDay($value)
    {
        $date = DateTime::createFromFormat(self::FORMAT_DATE, $value);
        $date->setTime(0, 0, 0);
        return $date->format(self::FORMAT_DB_DATETIME);
    }

    public static function getEndDay($value)
    {
        $date = DateTime::createFromFormat(self::FORMAT_DATE, $value);
        $date->setTime(23, 59, 59);
        return $date->format(self::FORMAT_DB_DATETIME);
    }

    public static function getJsExpressionPeriods()
    {
        return [
            Yii::t('main', "Сегодня") => ["moment().startOf('day')", "moment()"],
            Yii::t('main', "Вчера") => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
            Yii::t('main', "Текущая неделя") => ["moment().startOf('week')", "moment()"],
            Yii::t('main', "Прошлая неделя") => ["moment().startOf('week').subtract(7,'days')", "moment().startOf('week').subtract(1,'days')"],
            Yii::t('main', "Последние {n} дней", ['n' => 30]) => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
            Yii::t('main', "Этот месяц") => ["moment().startOf('month')", "moment()"],
            Yii::t('main', "Прошлый месяц") => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
            Yii::t('main', "Последние 3 месяца") => ["moment().subtract(2, 'month').startOf('month')", "moment()"],
            Yii::t('main', "Последние 6 месяцев") => ["moment().subtract(5, 'month').startOf('month')", "moment()"],
            Yii::t('main', "Последние 12 месяцев") => ["moment().subtract(11, 'month').startOf('month')", "moment()"],
            Yii::t('main', "Этот год") => ["moment().startOf('year')", "moment()"],
            Yii::t('main', 'За все время') => ["moment('2022-01-01')", "moment()"],
        ];
    }
}
