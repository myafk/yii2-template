<?php

namespace app\components\widgets;

use app\components\helpers\DateHelper;
use yii\helpers\Html;

class DateTimePicker extends \kartik\datetime\DateTimePicker
{

    public $convertFormat = true;
    public bool $convertValue = true;

    public function init()
    {
        $this->pluginOptions = array_merge_recursive([
            'format' => DateHelper::FORMAT_DATETIME_PHP
        ], $this->pluginOptions);

        if ($this->convertValue && $this->model && $this->model->{$this->attribute}) {
            $this->model->{$this->attribute} = date(str_replace('php:', '', $this->pluginOptions['format']), strtotime($this->model->{$this->attribute}));
        }

        parent::init();
    }

    protected function parseMarkup($input)
    {
        $parent = parent::parseMarkup($input);

        //Фикс бага с не отображением ошибки
        if ($this->model && $this->model->hasErrors($this->attribute)) {
            $parent .= Html::tag('div', '', ['class' => 'is-invalid']);
        }

        return $parent;
    }

}
