<?php

namespace app\modules\api\components;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class RestController extends ActiveController
{

    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];
        return $behaviors;
    }

}
