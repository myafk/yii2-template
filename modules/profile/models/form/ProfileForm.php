<?php

namespace app\modules\profile\models\form;

use app\components\validators\NullValidator;
use app\modules\user\models\exception\ProfileException;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;

class ProfileForm extends Model
{
    const SAVE_ATTRIBUTES = ['last_name', 'first_name', 'patronymic', 'function', 'phone'];

    public $last_name;
    public $first_name;
    public $patronymic;
    public $function;
    public $phone;

    public function init()
    {
        if (Yii::$app->user->isGuest) {
            throw new ProfileException('Please log in.', ProfileException::NOT_LOG_IN);
        }
        /** @var User $identity */
        $identity = Yii::$app->user->identity;

        $this->setAttributes($identity->toArray(self::SAVE_ATTRIBUTES));
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['last_name', 'filter', 'filter' => 'trim'],
            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2, 'max' => 128],

            ['first_name', 'filter', 'filter' => 'trim'],
            ['first_name', 'required'],
            ['first_name', 'string', 'min' => 2, 'max' => 128],

            ['patronymic', 'filter', 'filter' => 'trim'],
            ['patronymic', 'string', 'min' => 2, 'max' => 128],

            ['function', 'filter', 'filter' => 'trim'],
            ['function', 'string', 'min' => 2, 'max' => 128],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'string', 'min' => 2, 'max' => 128],
            ['phone', 'match', 'pattern' => '/^[0-9\+\-\(\)]+$/i'],

            [['patronymic', 'function', 'phone'], NullValidator::class]
        ];
    }

    public function attributeLabels()
    {
        return [
            'last_name' => Yii::t('main', 'Фалимия'),
            'first_name' => Yii::t('main', 'Имя'),
            'patronymic' => Yii::t('main', 'Отчество'),
            'function' => Yii::t('main', 'Должность'),
            'phone' => Yii::t('main', 'Телефон'),
            'parentErrors' => Yii::t('main', 'Ошибки пользователя'),
        ];
    }

    public function change()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $user->setAttributes($this->toArray(self::SAVE_ATTRIBUTES), false);
            $user->update(false, self::SAVE_ATTRIBUTES);
            return true;
        }

        return false;
    }
}