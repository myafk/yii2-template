<?php

namespace app\modules\user\models;

use app\components\helpers\ArrayHelper;
use app\components\traits\StatusInterface;
use app\components\traits\StatusTrait;
use app\modules\upload\components\AvatarTrait;
use app\modules\user\models\traits\UserQueryTrait;
use Yii;
use yii\bootstrap4\Html;

/**
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property $last_visit_at
 * @property $last_name
 * @property $first_name
 * @property $patronymic
 * @property $function
 * @property $phone
 */
class User extends \mdm\admin\models\User
{
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 1;
    const STATUS_ACTIVE = 10;

    use UserQueryTrait;
    use AvatarTrait;
    use StatusTrait;

    const SESSION_AUTH_TOKEN_KEY = 'session.auth_token';
    const SESSION_BACK_IDENTITY_ID = 'session.back_identity';

    public function behaviors()
    {
        return [];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'username' => Yii::t('main', 'Логин'),
            'email' => Yii::t('main', 'E-mail'),
            'status' => Yii::t('main', 'Статус'),
            'password_hash' => Yii::t('main', 'Пароль'),
            'created_at' => Yii::t('main', 'Дата создания'),
            'updated_at' => Yii::t('main', 'Дата обновления'),
            'last_visit_at' => Yii::t('main', 'Последнее посещение'),
            'last_name' => Yii::t('main', 'Фамилия'),
            'first_name' => Yii::t('main', 'Имя'),
            'patronymic' => Yii::t('main', 'Отчество'),
            'function' => Yii::t('main', 'Должность'),
            'phone' => Yii::t('main', 'Телефон'),
            'avatar_id' => Yii::t('main', 'Аватар'),

            'fullName' => Yii::t('main', 'ФИО'),
            'statusName' => Yii::t('main', 'Статус'),
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username'], 'string', 'min' => 2, 'max' => 64],
            [['username'], 'unique'],
            [['email'], 'string', 'min' => 3, 'max' => 250],
            [['email'], 'email'],
            [['email'], 'unique'],
            ['status', 'in', 'range' => array_keys(self::getStatuses())],
            [['last_name', 'first_name', 'patronymic', 'function', 'phone'], 'string', 'min' => 2, 'max' => 128],
            [['password_hash'], 'required'],
        ];
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getFullName()
    {
        $fio = trim(Html::encode($this->last_name
            . ($this->first_name ? ' ' . mb_substr($this->first_name, 0, 1, 'UTF-8') . '.' : '')
            . ($this->patronymic ? mb_substr($this->patronymic, 0, 1, 'UTF-8') . '.' : '')));
        return $fio ?: $this->username;
    }

    public static function getList(array $roles = []): array
    {
        $query = self::find()->andWhere(['status' => self::STATUS_ACTIVE]);
        if ($roles) {
            $query->leftJoin('{{%auth_assignment}} auth', 'auth.user_id = users.id');
            $query->andWhere(['item_name' => $roles]);
        }
        return ArrayHelper::map($query->all(), 'id', 'fullName');
    }

}
