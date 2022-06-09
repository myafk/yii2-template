<?php

use app\components\helpers\PermissionHelper as PH;

class m180913_000001_init_permissions extends \app\components\db\PermissionMigration
{

    public $add = [
        ['manager', [
            PH::CABINET_PROFILE,
            'user/user/logout-by-user',
            'profile/*',
            'main/*'
        ]],
        [PH::ROLE_ADMIN, [
            PH::CABINET_DASHBOARD,
            'dashboard/*',
            'user/user/index',
            'settings/default/index',
        ]],
        [PH::ROLE_ROOT, [
            'user/*',
            'settings/*',
            'upload/*',
            'log/*',
            PH::ACCESS_RBAC,
            PH::LOGIN_BY_USER,
            PH::ACCESS_TRANSLATE,
        ]],
    ];

}
