<?php

namespace app\commands\system;

use app\components\controllers\BaseConsoleController;
use app\modules\api\models\ApiToken;
use Yii;
use yii\helpers\Console;
use yii\helpers\Json;

class ApiController extends BaseConsoleController
{
    public function actionCreateToken()
    {
        $model = new ApiToken();
        $model->access_token = Yii::$app->security->generateRandomString(64);
        if ($model->save()) {
            $this->stdout("Token {$model->access_token} created success \n", Console::FG_GREEN);
        } else {
            $this->stdout("Token created error \n", Console::FG_RED);
            $this->stdout(Json::encode($model->getErrors()) . PHP_EOL, Console::FG_RED);
        }
    }

}
