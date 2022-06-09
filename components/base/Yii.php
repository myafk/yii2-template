<?php

namespace app\components\base;

use app\components\api\CrmApi;

class Yii extends \Yii
{

    public static function getCrmApi():CrmApi
    {
        return self::$app->get("crm");
    }

}
