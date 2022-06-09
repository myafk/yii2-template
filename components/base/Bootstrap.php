<?php

namespace app\components\base;

use app\components\validators\DateValidator;
use yii\base\BootstrapInterface;
use yii\validators\Validator;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Validator::$builtInValidators['date'] = DateValidator::class;
        Yii::$classMap['yii\helpers\Html'] = '@app/components/base/Html.php';
        Yii::$container->set('yii\bootstrap4\ActiveField', ['class' => 'app\components\base\ActiveField']);
    }
}
