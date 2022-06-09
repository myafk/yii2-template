<?php

namespace app\modules\upload\models;

use app\modules\upload\components\UploadHelper;
use app\modules\user\models\User;
use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "uploads".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $model
 * @property string $model_attribute
 * @property integer $object_id
 * @property string $title
 * @property string $description
 * @property string $mime
 * @property string $path
 * @property integer $sort
 * @property string $created_at
 *
 * @property User $user
 */
class Attachment extends ActiveRecord
{
    const TYPE_IMAGES = 'image';
    const TYPE_DOCUMENTS = 'application';

    const REGEX_IMAGE = 'gif|jpe?g|png';
    const REGEX_DOCUMENT = 'docx?|xlsx?|plain|rtf|pdf';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attachments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path'], 'required'],
            [['user_id', 'object_id', 'sort'], 'integer'],
            [['title', 'model_attribute'], 'string', 'max' => 256],
            [['description'], 'string'],
            [['mime'], 'string', 'max' => 128],
            [['path'], 'string', 'max' => 256],
            [['model'], 'string', 'max' => 256],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('main', 'ID Пользователя'),
            'model' => Yii::t('main', 'Модель'),
            'model_attribute' => Yii::t('main', 'Атрибут модели'),
            'object_id' => Yii::t('main', 'ID Модели'),
            'title' => Yii::t('main', 'Название'),
            'description' => Yii::t('main', 'Описание'),
            'mime' => Yii::t('main', 'Mime Type'),
            'path' => Yii::t('main', 'Путь'),
            'sort' => Yii::t('main', 'Порядок'),
            'created_at' => Yii::t('main', 'Дата создания'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->user_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function mainUploadPath()
    {
        return Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR;
    }

    public static function mainUrlPath()
    {
        return Yii::$app->request->hostInfo . '/uploads/';
    }

    public function afterDelete()
    {
        $path = self::mainUploadPath() . "$this->model/$this->path";
        if (file_exists($path)) {
            unlink($path);
        }

        $thumb = self::mainUploadPath() . "$this->model/thumbs/$this->path";
        if (file_exists($thumb)) {
            unlink($thumb);
        }

        $title = self::mainUploadPath() . "$this->model/title/$this->path";
        if (file_exists($title)) {
            unlink($title);
        }
        return parent::afterDelete();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return !$this->isNewRecord
            ? self::mainUrlPath() . "$this->model/$this->path"
            : null;
    }

    /**
     * @return string
     */
    public function getTitleUrl()
    {
        if (file_exists(self::mainUploadPath() . "$this->model/title/$this->path")) {
            return self::mainUrlPath() . "$this->model/title/$this->path";
        } else {
            return $this->getUrl();
        }
    }

    /**
     * @return string
     */
    public function getThumbUrl()
    {
        if (file_exists(self::mainUploadPath() . "$this->model/thumbs/$this->path")) {
            return self::mainUrlPath() . "$this->model/thumbs/$this->path";
        } else {
            return $this->getTitleUrl();
        }
    }

    /**
     * @return string
     */
    public function getFancyImg()
    {
        return Html::a(Html::img($this->getThumbUrl(), ['class' => 'many-pic']), $this->getUrl(), [
            'class' => 'zoom noajax', 'rel' => 'zoom'
        ]);
    }

    /**
     * @param null $id
     * @return array|string
     */
    public function getMimeType($id = null)
    {
        $data = explode('/', $this->mime);
        if ($id !== null) {
            return $data[$id] ?? '';
        }
        return $data;
    }

    /**
     * @return bool
     */
    public function isTypeImage()
    {
        return !!preg_match('/(' . self::REGEX_IMAGE . ')/i', $this->getMimeType(1));
    }

    /**
     * @return bool
     */
    public function isTypeDocument()
    {
        return !!preg_match('/(' . self::REGEX_DOCUMENT . ')/i', $this->getMimeType(1));
    }

    /**
     * Получение приложений по id и классу модели
     * @param \yii\db\ActiveRecord $model
     * @param string $attribute
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getModelAttachments(ActiveRecord $model, $attribute)
    {
        if ($model->isNewRecord) {
            return [];
        }
        return static::find()->where([
            'model' => UploadHelper::getClassName($model),
            'model_attribute' => $attribute,
            'object_id' => $model->getPrimaryKey()
        ])->orderBy(['sort' => SORT_DESC])->all();
    }

    /**
     * Получение приложения по значению аттрибута модели
     * @param integer $id
     * @return Attachment|bool|null
     */
    public static function getAttributeAttachment($id)
    {
        if ($id === NULL) {
            return false;
        }
        return static::find()->where([
            'id' => $id
        ])->one();
    }

    /**
     * Получение приложений по ID. Строковый цифры через зяпятую или массив
     * @param string|array $ids
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public static function getAttachmentsByIds($ids)
    {
        if ($ids === NULL) {
            return [];
        }
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        return static::find()->where([
            'id' => $ids
        ])->orderBy(['sort' => SORT_DESC])->all();
    }

}
