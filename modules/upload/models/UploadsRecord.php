<?php

namespace app\modules\upload\models;

use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use app\modules\upload\models\Attachment;

/**
 * Моделька, от которой следует наследоваться, если хотим иметь в поле
 * images разделяемые запятой ID-шники. Используется преимущественно
 * в виджетах модуля uploads, типа $form->field($model, 'images')->widget...
 * а далее, все само разруливается и сохраняется
 *
 * @property integer $id
 */
class UploadsRecord extends ActiveRecord
{
    /** @var array - attributes for upload */
    protected $_uploads = [];

    public function __get($name)
    {
        if (isset($this->_uploads[$name]) || array_key_exists($name, $this->_uploads)) {
            return $this->_uploads[$name];
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (isset($this->_uploads[$name]) || array_key_exists($name, $this->_uploads)) {
            $this->_uploads[$name] = $value;
            return;
        }
        parent::__set($name, $value);
    }
    
    public function afterFind()
    {
        $this->loadUploadsFields();
        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            Attachment::updateAll(['object_id' => null], [
                'object_id' => $this->id,
                'model' => StringHelper::basename(get_class($this))
            ]);
        }

        foreach ($this->_uploads as $key => $upload) {
            if (!empty($upload)) {
                $upload = explode(",", $upload);
                Attachment::updateAll(['object_id' => $this->id], ['id' => $upload, 'model_attribute' => $key]);
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function getUploadAttributes()
    {
        return array_keys($this->_uploads);
    }

    /**
     * @param string|null|bool $attribute
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getUploads($attribute = false)
    {
        $query = Attachment::find()->where([
            'model' => StringHelper::basename(get_class($this)),
            'object_id' => $this->id
        ]);
        if ($attribute !== false) {
            $query->andWhere(['model_attribute' => $attribute]);
        }
        return $query->all();
    }

    /**
     * @param string|null $attribute
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getUploadsByAttribute($attribute)
    {
        return $this->getUploads($attribute);
    }
    
    public function loadUploadsFields()
    {
        foreach ($this->_uploads as $key => $_) {
            $ids = Attachment::find()->where([
                'model' => StringHelper::basename(get_class($this)),
                'object_id' => $this->id,
                'model_attribute' => $key
            ])->select('id')->column();
            $this->_uploads[$key] = implode(',', $ids);
        }
    }
}
