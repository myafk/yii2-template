<?php

namespace app\modules\user\models\form;

use app\components\helpers\PermissionHelper;
use app\modules\user\models\User;
use Yii;

class UserCreateForm extends User
{
    public $newPassword;
    public $role;

    public function rules()
    {
        return [
            [['username', 'email', 'role'], 'required'],
            [['username'], 'string', 'min' => 2, 'max' => 64],
            [['username'], 'unique'],
            [['email'], 'string', 'min' => 3, 'max' => 250],
            [['email'], 'email'],
            [['email'], 'unique'],
            ['role', 'in', 'range' => array_keys(PermissionHelper::listUserRolesByUser())],
            ['status', 'in', 'range' => array_keys(self::getStatuses())],
            [['last_name', 'first_name', 'patronymic', 'function', 'phone'], 'string', 'min' => 2, 'max' => 128],

            [['last_name', 'first_name', 'patronymic', 'function', 'phone', 'username', 'email'],
                'filter', 'filter' => 'trim'],
            [['last_name', 'first_name', 'patronymic', 'function', 'phone', 'username'],
                'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            [['newPassword'], 'required'],
            [['newPassword'], 'string', 'min' => 6, 'max' => 64],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'role' => Yii::t('main', 'Роль'),
            'newPassword' => Yii::t('main', 'Пароль')
        ]);
    }

    public function beforeSave($insert)
    {
        $this->setPassword($this->newPassword);
        $this->generateAuthKey();

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $authManager = Yii::$app->authManager;
        $authRole = $authManager->getRole($this->role);
        if (!$authManager->assign($authRole, $this->id)) {
            Yii::error("Error assign ID:{$this->id} ROLE:{$this->role}", 'user-create');
        }

        parent::afterSave($insert, $changedAttributes);
    }
}