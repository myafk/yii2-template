<?php

namespace app\components\db;

use Exception;
use Yii;
use yii\db\Migration;

class PermissionMigration extends Migration
{
    public $remove = [];
    public $add = [];
    /** @var  \yii\rbac\ManagerInterface */
    public $authManager;

    public function init()
    {
        parent::init();
        $this->authManager = Yii::$app->getAuthManager();
    }

    public function safeUp()
    {
        foreach ($this->remove as $item) {
            if(count($item) < 2) {
                throw new Exception('Input data error #1');
            }
            if(is_array($item[1])){
                foreach ($item[1] as $permission) {
                    $this->revokeRolesPermission($permission, $item[0]);
                }
            } elseif (is_string($item[1])) {
                $this->revokeRolesPermission($item[1], $item[0]);
            } else {
                throw new Exception('Input data error #2');
            }
        }

        foreach ($this->add as $item) {
            if(count($item) < 2) {
                throw new Exception('Input data error #1');
            }
            if(is_array($item[1])){
                foreach ($item[1] as $permission) {
                    $this->createAndAssignRolesPermission($permission, $item[0]);
                }
            } elseif (is_string($item[1])) {
                $this->createAndAssignRolesPermission($item[1], $item[0]);
            } else {
                throw new Exception('Input data error #2');
            }
        }
    }

    public function safeDown()
    {
        foreach ($this->add as $item) {
            if(count($item) < 2) {
                throw new Exception('Input data error #1');
            }
            if(is_array($item[1])){
                foreach ($item[1] as $permission) {
                    $this->revokeRolesPermission($permission, $item[0]);
                }
            } elseif (is_string($item[1])) {
                $this->revokeRolesPermission($item[1], $item[0]);
            } else {
                throw new Exception('Input data error #2');
            }
        }

        foreach ($this->remove as $item) {
            if(count($item) < 2) {
                throw new Exception('Input data error #1');
            }
            if(is_array($item[1])){
                foreach ($item[1] as $permission) {
                    $this->createAndAssignRolesPermission($permission, $item[0]);
                }
            } elseif (is_string($item[1])) {
                $this->createAndAssignRolesPermission($item[1], $item[0]);
            } else {
                throw new Exception('Input data error #2');
            }
        }
    }
    /**
     * Создает и назначает массиву ролей определенный пермишен
     *
     * @param $permissionName
     * @param array $roleNames
     * @throws Exception
     */
    public function createAndAssignRolesPermission($permissionName, $roleNames, $permissionDescription = null)
    {
        if (!$permission = $this->createOrGetPermission($permissionName, $permissionDescription ?? $permissionName)) {
            throw new Exception('Invalid permission name ' . $permissionName);
        }

        if (is_string($roleNames)) $roleNames = [$roleNames];

        foreach ($roleNames as $roleName) {
            if (!$role = $this->authManager->getRole($roleName)) {
                throw new Exception('Invalid role name ' . $roleName);
            }

            if ($permission && !$this->authManager->hasChild($role, $permission)) {
                $this->authManager->addChild($role, $permission);
            }
        }
    }

    /**
     * Удалить у ролей определенное разрешение
     *
     * @param string $permissionName
     * @param array|string $roleNames
     * @throws Exception
     * @return bool
     */
    public function revokeRolesPermission($permissionName, $roleNames)
    {
        if (!$permission = $this->authManager->getPermission($permissionName)) {
            return false;
        }

        if (is_string($roleNames)) $roleNames = [$roleNames];

        foreach ($roleNames as $roleName) {
            if (!$role = $this->authManager->getRole($roleName)) {
                throw new Exception('Invalid role name ' . $roleName);
            }

            if ($permission && $this->authManager->hasChild($role, $permission)) {
                $this->authManager->removeChild($role, $permission);
            }
        }
        return true;
    }

    /**
     * Получает Permission по имени. Создает, если отсутствует
     *
     * @param string $permissionName
     * @param string $permissionDescription
     * @param null $ruleName
     * @return null|\yii\rbac\Permission
     */
    public function createOrGetPermission($permissionName, $permissionDescription, $ruleName = null)
    {
        $permission = $this->authManager->getPermission($permissionName);
        if (!$permission) {
            $permission = $this->authManager->createPermission($permissionName);
            $permission->description = $permissionDescription;
            $permission->ruleName = $ruleName;

            $this->authManager->add($permission);
        }

        return $permission;
    }

    /**
     * Удаляет Permission по имени.
     *
     * @param string $permissionName
     * @param bool $error
     * @throws Exception
     */
    public function removePermission($permissionName, $error = false)
    {
        $permission = $this->authManager->getPermission($permissionName);
        if ($permission) {
            $this->authManager->remove($permission);
        } else if ($error) {
            throw new Exception('Permission is not exist: ' . $permissionName . '. Cant delete it.');
        }
    }
}