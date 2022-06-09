<?php

namespace app\components\helpers;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class MdmAccessControl
 */

class MdmAccessControl extends \yii\base\ActionFilter
{

    public function beforeAction($action)
    {
        if(PermissionHelper::checkPermission(PermissionHelper::ACCESS_RBAC)) {
            return true;
        }
        $this->denyAccess();
    }

    /**
     * @throws ForbiddenHttpException
     */
    protected function denyAccess()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('main', 'Доступ запрещен'));
        }
    }
}