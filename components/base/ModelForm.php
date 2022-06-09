<?php

namespace app\components\base;

use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class ModelForm
 *
 * @property ActiveRecord $record
 * @property array $errorsSummary
 * @property bool $isNewRecord
 */
class ModelForm extends Model
{

    protected ActiveRecord $_record;
    protected array $_errorsSummary = [];
    protected bool $_isNewRecord;

    public function __construct(ActiveRecord $record, $config = [])
    {
        $this->setRecord($record);

        parent::__construct($config);
    }

    public function getRecord(): ActiveRecord
    {
        return $this->_record;
    }

    public function getIsNewRecord(): bool
    {
        return $this->_record->isNewRecord;
    }

    public function setRecord(ActiveRecord $record)
    {
        $this->_record = $record;
        if (!$record->isNewRecord) {
            $this->setAttributes($record->attributes, false);
        }
    }

    public function getErrorsSummary()
    {
        return $this->_errorsSummary;
    }

    public function addErrorsSummary(array $errors)
    {
        foreach ($errors as $attribute => $allErrors) {
            foreach ($allErrors as $error) {
                $this->addError($attribute, $error);
            }
        }
    }

    public function addError($attribute, $error = '')
    {
        parent::addError($attribute, $error);
        $this->_errorsSummary[Html::getInputId($this, $attribute)][] = $error;
    }

    public function prepare(): bool
    {
        if ($this->validate()) {
            $this->_record->setAttributes($this->attributes, false);
            if (!$this->_record->validate()) {
                $this->addErrorsSummary($this->_record->errors);
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    public function save(): bool
    {
        if ($this->prepare()) {
            return $this->_record->save(false);
        }

        return false;
    }

    public function attributeLabels(array $attributeLabels = []): array
    {
        return array_merge($this->_record->attributeLabels(), $attributeLabels);
    }

}
