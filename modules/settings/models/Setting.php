<?php

namespace app\modules\settings\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class Settings
 * @package app\modules\settings\models
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string $comment
 * @property int $is_json
 * @property string $view
 */
class Setting extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            [['comment'], 'string'],
            [['is_json'], 'integer'],
            [['key', 'view'], 'string', 'max' => 64],
            [['key'], 'unique'],
            ['value', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => Yii::t('main', 'Ключ'),
            'value' => Yii::t('main', 'Значение'),
            'comment' => Yii::t('main', 'Комментарий'),
            'is_json' => Yii::t('main', 'JSON'),
            'view' => Yii::t('main', 'Страница'),
        ];
    }

    public function afterFind()
    {
        if ($this->is_json) {
            $this->value = Json::decode($this->value);
        }
        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($this->is_json) {
            $this->value = Json::encode($this->value);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @var array
     */
    protected static $_values = [];

    /**
     * @param string $key
     * @param bool $cache
     *
     * @return bool|mixed
     */
    public static function getValueByKey($key, $cache = true)
    {
        if (!$cache || !isset(self::$_values[$key])) {
            $model = self::findOne(['key' => $key]);
            if (!$model) {
                return false;
            }
            self::$_values[$key] = $model->value;
        }

        return self::$_values[$key];
    }
}
