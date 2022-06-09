<?php

namespace app\modules\upload\components;

use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

class UploadHelper
{
    public static function getClassName(ActiveRecord $model)
    {
        return $model->hasMethod('getClassName')
            ? $model->getClassName()
            : StringHelper::basename(get_class($model));
    }
}