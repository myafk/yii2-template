<?php

namespace app\components\validators;

use app\components\helpers\DateHelper;
use yii\validators\Validator;

class DateValidator extends \yii\validators\DateValidator
{

    public bool $autoConvert = false;

    public function init()
    {
        if (!$this->format) {
            $this->format = DateHelper::FORMAT_DATETIME_PHP;
        }
        parent::init();
    }

    public function validateAttribute($model, $attribute)
    {
        parent::validateAttribute($model, $attribute);

        if (!$model->hasErrors($attribute) && $model->{$attribute}) {
            $model->{$attribute} = date(DateHelper::FORMAT_DB_DATETIME, strtotime($model->{$attribute}));
        }
    }

}
