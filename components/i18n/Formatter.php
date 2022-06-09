<?php

namespace app\components\i18n;

use app\components\helpers\DateHelper;
use yii\bootstrap4\Html;
use yii\helpers\Json;

class Formatter extends \yii\i18n\Formatter
{
    public $dateFormat = DateHelper::FORMAT_DATE_PHP;
    public $datetimeFormat = DateHelper::FORMAT_DATETIME_PHP;
    public $defaultTimeZone = 'Europe/Moscow';

    public function asCheck($value)
    {
        $icon = $value ? 'plus' : 'minus';
        return Html::tag('i', '', ['class' => 'fas fa-' . $icon]);
    }
    
    public function asImplode($value)
    {
        if (is_array($value)) {
            return implode('<br>', array_map(function ($el) {
                return Html::encode($el);
            }, $value));
        }
        return $this->asText($value);
    }
    
    public function asJson($value)
    {
        return Json::encode($value);
    }

    public function asJsoncut($value)
    {
        $value = Json::encode($value);
        return mb_strlen($value) > 100
            ? mb_substr($value, 0, 100) . '...'
            : $value;
    }

    public function asDaterange($value, $format = null)
    {
        return $this->asDate($value, $format);
    }

    public function asDatetimerange($value, $format = null)
    {
        return $this->asDatetime($value, $format);
    }

}
