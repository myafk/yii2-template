<?php

namespace app\modules\profile\models\form;

use app\modules\user\models\User;
use Yii;
use yii\base\Model;

class ChangePassword extends Model
{
    public $oldPassword;
    public $newPassword;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'retypePassword'], 'required'],
            [['oldPassword'], 'validatePassword'],
            [['newPassword'], 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => Yii::t('main', 'Старый пароль'),
            'newPassword' => Yii::t('main', 'Новый пароль'),
            'retypePassword' => Yii::t('main', 'Повторите новый пароль'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', Yii::t('main', 'Старый пароль не совпадает'));
        }
    }

    /**
     * Change password.
     * @return bool
     */
    public function change()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}