<?php

namespace app\components\helpers;

use app\modules\user\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

/**
 * Класс определяющий права по всему сайту. Используется в классах
 * BaseController и AbstractMenu для определения прав экшенов,
 * так же позволяет определять отдельные права
 * Class PermissionHelper
 */
class PermissionHelper
{
    // На продакшене константы false, false
    /** Разрешить всем */
    const ALLOW_ALL = false;
    /** Разрешить не гостям */
    const ALLOW_NO_GUEST = false;
    
    const ROLE_ROOT = 'root';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_GUEST = 'guest';

    // Отдельные права объявляем константами
    const ACCESS_RBAC = 'RbacAdmin';
    const LOGIN_BY_USER = 'LoginByUser';
    const ACCESS_TRANSLATE = 'AccessTranslate';
    const ACCESS_DEBUG = 'AccessDebug';

    // Кабинет после входа
    const CABINET_DASHBOARD = 'CabinetDashboard';
    const CABINET_PROFILE = 'CabinetProfile';

    private static $_userRoles = null;
    private static $_roles = null;

    /**
     * Основная функция возвращает правду, если пользователь имеет право
     * При константе ALLOW_ALL = true, всегда возвращает true
     * При константе ALLOW_NO_GUEST = true, возвращает true, когда пользователь не гость
     * Примеры прав:
     *  self::checkPermission('RbacAdmin')
     *      выдаст правду, если на роли объявлено право RbacAdmin
     *  self::checkPermission('module/controller/action', true);
     *      выдаст правду, если на роли объявлено любое из прав:
     *       module/*
     *       module/controller/*
     *       module/controller/action
     *  self::checkPermission('module/controller/action');
     *      выдаст правду, если на роли объявлено право:
     *       module/controller/action
     *
     * @param string $permission
     * @param bool $isRoute - определять право, как путь
     * @return bool
     */
    public static function checkPermission($permission, $isRoute = false)
    {
        if (self::ALLOW_ALL) {
            return true;
        }
        if (self::ALLOW_NO_GUEST) {
            return !Yii::$app->user->isGuest;
        }
        if (Yii::$app instanceof \yii\console\Application) {
            return true;
        }
        if ($isRoute) {
            $permission = preg_replace('/^\//', '', $permission);
            $partPermission = explode('/', $permission);
            if (count($partPermission) == 3) {
                return Yii::$app->user->can($partPermission[0] . '/*')
                    || Yii::$app->user->can($partPermission[0] . '/' . $partPermission[1] . '/*')
                    || Yii::$app->user->can($permission);
            }
        }
        return Yii::$app->user->can($permission);
    }

    public static function getUserRoles(): array
    {
        if (self::$_userRoles === null) {
            self::$_userRoles = array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
        }
        return self::$_userRoles;
    }

    public static function getUserRole(int|null $id = null): Role|null
    {
        $roles = Yii::$app->authManager->getRolesByUser($id ?: Yii::$app->user->id);
        if (!empty($roles)) {
            return array_shift($roles);
        }

        return null;
    }

    /**
     * @return array
     */
    public static function listUserRolesByUser()
    {
        if (Yii::$app instanceof \yii\console\Application) {
            return self::getAllRoles();
        }
        $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        $role = array_shift($roles);
        switch ($role->name) {
            case self::ROLE_ROOT:
                return self::getAllRoles();

            case self::ROLE_ADMIN:
                $data = self::getAllRoles();
                unset($data[self::ROLE_ROOT]);
                return $data;
        }
        return [];
    }

    /**
     * Возвращает все возможные роли
     * @return array|null
     */
    public static function getAllRoles()
    {
        if (self::$_roles === null) {
            $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
            unset($roles[self::ROLE_GUEST]);
            self::$_roles = $roles;
        }

        return self::$_roles;
    }

    /**
     * Проверка роли пользователя
     * Функция возвращает правду, если пользователь имеет роль
     * При константе ALLOW_ALL = true, всегда возвращает true
     * При константе ALLOW_NO_GUEST = true, возвращает true, когда пользователь не гость
     * @param array|string $checkRoles
     * @return bool
     */
    public static function checkRole($checkRoles)
    {
        if (self::ALLOW_ALL) {
            return true;
        }
        if (self::ALLOW_NO_GUEST) {
            return !Yii::$app->user->isGuest;
        }
        if (empty($checkRoles)) {
            return false;
        }
        if (!is_array($checkRoles)) {
            $checkRoles = [$checkRoles];
        }
        $intersect = array_intersect($checkRoles, self::getUserRoles());
        return !empty($intersect);
    }

    public static function isMatchIp($ip, array $validIps)
    {
        if (Yii::$app->params['disableIpCheck'] === true) {
            return true;
        }
        if (empty($validIps)) {
            return true;
        }

        foreach ($validIps as $rule) {
            if ($rule === '*' || $rule === $ip || (($pos = strpos($rule, '*')) !== false && !strncmp($ip, $rule, $pos))) {
                return true;
            }
        }

        return false;
    }
}
