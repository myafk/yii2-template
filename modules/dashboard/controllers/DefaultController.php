<?php

namespace app\modules\dashboard\controllers;

use app\components\controllers\BaseController;
use app\modules\user\models\User;
use yii\db\Expression;
use yii\db\Query;

/**
 * Default controller for the `dashboard` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        // Для теста
        $usersCount = (new Query())
            ->from(User::tableName())
            ->count();
        $activeUsersCount = (new Query())
            ->from(User::tableName())
            ->andWhere(['>=', 'last_visit_at', new Expression('DATE_ADD(NOW(), INTERVAL -2 DAY)')])
            ->count();

        return $this->render('index', [
            'usersCount' => $usersCount,
            'activeUsersCount' => $activeUsersCount,
        ]);
    }
}
