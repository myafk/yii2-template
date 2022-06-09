<?php

namespace app\modules\user\models\form;

use app\components\helpers\PermissionHelper;
use app\modules\user\models\User;
use Yii;

class UserUpdateForm extends User
{
    public $newPassword;
    public $retypePassword;
    public $role;

    public function rules()
    {
        return [
            [['email', 'role'], 'required'],
            [['email'], 'string', 'min' => 3, 'max' => 250],
            [['email'], 'email'],
            [['email'], 'unique'],
            ['role', 'in', 'range' => array_keys(PermissionHelper::listUserRolesByUser())],
            ['status', 'in', 'range' => array_keys(self::getStatuses())],
            [['last_name', 'first_name', 'patronymic', 'function', 'phone'], 'string', 'min' => 2, 'max' => 128],

            [['last_name', 'first_name', 'patronymic', 'function', 'phone', 'email'],
                'filter', 'filter' => 'trim'],
            [['last_name', 'first_name', 'patronymic', 'function', 'phone'],
                'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            [['newPassword'], 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'role' => Yii::t('main', 'Роль'),
            'newPassword' => Yii::t('main', 'Новый пароль'),
            'retypePassword' => Yii::t('main', 'Повторите пароль'),
        ]);
    }

    public function afterFind()
    {
        $this->role = (PermissionHelper::getUserRole($this->id))->name ?? null;

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($this->newPassword) {
            $this->setPassword($this->newPassword);
            $this->generateAuthKey();
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($this->id);
        $role = array_shift($roles);

        if ($role->name != $this->role) {
            $authManager->revokeAll($this->id);

            $authRole = $authManager->getRole($this->role);
            if (!$authManager->assign($authRole, $this->id)) {
                Yii::error("Error assign ID:{$this->id} ROLE:{$this->role}", 'user-update');
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
