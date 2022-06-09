<?php

namespace app\modules\settings\controllers;

use app\components\controllers\BaseController;
use Yii;

/**
 * Default controller for the `settings` module
 */
class SystemController extends BaseController
{

    public function actionFlushCache()
    {
        if (Yii::$app->cache->flush()) {
            $this->setSuccessFlash(Yii::t('main', 'Кеш очищен'));
        } else {
            $this->setDangerFlash(Yii::t('main', 'Ошибка очистки кеша'));
            $this->setDangerFlash(Yii::$app->cache->getMemcache()->getResultCode());
        }
        return $this->redirect(Yii::$app->request->referrer ?? '/');
    }

    public function actionTest()
    {
        $a = new \app\modules\product\models\ProductCategory();
        $a->validateParent();
    }
}
