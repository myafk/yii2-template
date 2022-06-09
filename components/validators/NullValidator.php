<?php

namespace app\components\validators;

use yii\validators\Validator;

class NullValidator extends Validator
{
    public $skipOnEmpty = false;
    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (empty($value)) {
            $model->$attribute = null;
        }
    }

}
