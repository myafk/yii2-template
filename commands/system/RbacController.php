<?php

namespace app\commands\system;

use app\components\controllers\BaseConsoleController;
use app\components\helpers\PermissionHelper;
use app\modules\user\models\User;
use Yii;
use yii\helpers\Console;
use yii\helpers\Json;

class RbacController extends BaseConsoleController
{

    /**
     * Initial RBAC action
     */
    public function actionInit()
    {
        if (User::find()->count() > 0) {
            $this->stdout('Users already initialized' . PHP_EOL, Console::FG_RED);
            return;
        }
        $roles = Yii::$app->authManager->getRoles();
        foreach ($roles as $role) {
            $roleName = $role->name;
            if ($roleName == PermissionHelper::ROLE_GUEST) {
                continue;
            }
            $this->actionCreateUser($roleName, $roleName . '-test');
            $this->manageRole('assign', $roleName, $roleName);
        }
    }

    /**
     * @params string $username, string $password
     */
    public function actionCreateUser($username, $password)
    {
        $model = new User();
        $model->username = $username;
        $model->email = $username . '@rbac.ru';
        $model->setPassword($password);
        $model->status = User::STATUS_ACTIVE;
        if ($model->save()) {
            $this->stdout("User $username created success \n", Console::FG_GREEN);
        } else {
            $this->stdout("User $username created error \n", Console::FG_RED);
            $this->stdout(Json::encode($model->getErrors()) . PHP_EOL, Console::FG_RED);
        }
    }

    /**
     * @params string $username, string $password
     */
    public function actionChangePassword($username, $password)
    {
        $this->stdout(sprintf("Change password on user '%s' set to '%s' \n", $username, $password));
        $user = User::findByUsername($username);
        if ($user === null) {
            $this->stdout("User not found \n", Console::FG_RED);
            exit();
        }
        $user->setPassword($password);
        if ($user->save()) {
            $this->stdout("Success\n", Console::FG_GREEN);
        } else {
            $this->stdout("Error\n", Console::FG_RED);
            $this->stdout(Json::encode($user->getErrors()) . PHP_EOL, Console::FG_RED);
        }
    }

    /**
     * @params string $username, string $role
     */
    public function actionAssignRole($username, $role)
    {
        $this->manageRole('assign', $username, $role);
    }

    /**
     * @params string $username, string $role
     */
    public function actionRevokeRole($username, $role)
    {
        $this->manageRole('revoke', $username, $role);
    }

    /**
     * @param string $action
     * @param string $username
     * @param string $role
     */
    private function manageRole($action, $username, $role)
    {
        $this->stdout(sprintf("$action '%s' role to '%s' \n", $role, $username));
        $user = User::findByUsername($username);
        if ($user === null) {
            $this->stdout("User not found \n", Console::FG_RED);
            exit();
        }

        $authManager = Yii::$app->authManager;
        $authRole = $authManager->getRole($role);
        if ($authRole === null) {
            $this->stdout(sprintf("Role %s not found \n", $role), Console::FG_RED);
            exit();
        }

        try {
            $authManager->$action($authRole, $user->id);
        } catch (\Exception $e) {
            $this->stdout(
                sprintf("Role %s has already been %s to the user \n", $role, $action),
                Console::FG_RED
            );
            exit();
        }

        $this->stdout(sprintf("Role %s has been %s\n", $role, $action), Console::FG_GREEN);
    }

    public function actionFlushCache()
    {
        if (Yii::$app->cache->flush()) {
            $this->stdout("Cache clear success \n", Console::FG_GREEN);
        } else {
            $this->stdout("Cache clear error \n", Console::FG_RED);
        }
    }

}