<?php

namespace app\commands\system;

use app\components\controllers\BaseConsoleController;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

class InitController extends BaseConsoleController
{
    public function actionIndex()
    {
        $transation = Yii::$app->db->beginTransaction();
        try {

            $transation->commit();
        } catch (\Exception $e) {
            $this->stdout($e->getMessage());
            $transation->rollBack();
        }
    }

}