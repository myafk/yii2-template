<?php

namespace app\modules\user\models\logs;

/**
 * Класс будет использоваться для логирования действий пользователя.
 * Дорабатывать при необходимости
 * @deprecated
 */
class HistoryLog
{

    /**
     * @param string $url
     * @param integer $time
     * @return bool
     */
    public static function setLog($url, $time)
    {
        return true;
    }
}