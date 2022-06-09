<?php

namespace app\components\widgets;

use app\components\base\ActiveField;

/**
 */
class ActiveForm extends \yii\bootstrap4\ActiveForm
{

    public function field($model, $attribute, $options = []):ActiveField
    {
        return parent::field($model, $attribute, $options);
    }

}
