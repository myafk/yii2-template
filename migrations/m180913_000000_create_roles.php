<?php

use yii\db\Migration;
use app\components\helpers\PermissionHelper as PH;

class m180913_000000_create_roles extends Migration
{
    /** @var  \yii\rbac\ManagerInterface */
    public $authManager;
    
    public function init()
    {
        // USE BEFORE MIGRATE
        // yii migrate --migrationPath=@yii/rbac/migrations
        parent::init();
        $this->authManager = \Yii::$app->authManager;
    }
    
    public function safeUp()
    {
        $roleGuest = $this->authManager->createRole(PH::ROLE_GUEST);
        $roleGuest->description = 'Гость';
        $this->authManager->add($roleGuest);
        
        $roleManager = $this->authManager->createRole('manager');
        $roleManager->description = 'Менеджер';
        $this->authManager->add($roleManager);
        $this->authManager->addChild($roleManager, $roleGuest);
        
        $roleAdmin = $this->authManager->createRole(PH::ROLE_ADMIN);
        $roleAdmin->description = 'Админ';
        $this->authManager->add($roleAdmin);
        $this->authManager->addChild($roleAdmin, $roleManager);
        
        $roleRoot = $this->authManager->createRole(PH::ROLE_ROOT);
        $roleRoot->description = 'Суперпользователь';
        $this->authManager->add($roleRoot);
        $this->authManager->addChild($roleRoot, $roleAdmin);
    }
    
    public function safeDown()
    {
        if ($this->authManager->getRole(PH::ROLE_GUEST)) {
            $this->authManager->remove($this->authManager->getRole(PH::ROLE_GUEST));
        }
        if ($this->authManager->getRole(PH::ROLE_MANAGER)) {
            $this->authManager->remove($this->authManager->getRole(PH::ROLE_MANAGER));
        }
        if ($this->authManager->getRole(PH::ROLE_ADMIN)) {
            $this->authManager->remove($this->authManager->getRole(PH::ROLE_ADMIN));
        }
        if ($this->authManager->getRole(PH::ROLE_ROOT)) {
            $this->authManager->remove($this->authManager->getRole(PH::ROLE_ROOT));
        }
    }
}
