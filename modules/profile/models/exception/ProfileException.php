<?php

namespace app\modules\user\models\exception;

use yii\base\Exception;

class ProfileException extends Exception
{
    const NOT_LOG_IN = 1;
}