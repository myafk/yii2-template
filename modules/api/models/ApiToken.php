<?php

namespace app\modules\api\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "api_tokens".
 *
 * @property int $id
 * @property string $access_token
 */
class ApiToken extends \yii\db\ActiveRecord implements IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%api_tokens}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token'], 'required'],
            [['access_token'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'access_token' => Yii::t('main', 'Access Token'),
        ];
    }

    public function getId()
    {
        return $this->primaryKey;
    }

    public static function findIdentity($id)
    {
        return;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getAuthKey()
    {
        return;
    }

    public function validateAuthKey($authKey)
    {
        return;
    }
}
