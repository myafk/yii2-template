<?php

/**
 * Copyright https://github.com/nsept/yii2-birthday-picker
 * Some refactor
 */

namespace app\components\widgets;

use app\components\widgets\assets\BirthdayPickerAsset;
use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Json;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * Class BirthdayPickerWidget
 * @package app\components\widgets
 * @var $pluginOptions array
 *  - if updateAny set ==true change hidden anyway
 *  - template string with value separated by a comma [year,month,day,label,clear,age]
 */
class BirthdayPickerWidget extends InputWidget
{
    /**
     * @var \yii\widgets\ActiveForm
     */
    public $form;

    /**
     * @var string
     */
    public $template = "{label}\n{input}\n{error}";

    /**
     * @var array
     */
    public $pluginOptions = [];
    /**
     * @var string|null
     */
    public $ageAttribute = null;
    public $ageValue = null;

    /**
     * @return array
     */
    protected function getMonthNames()
    {
        return [
            Yii::t('main', 'Январь'),
            Yii::t('main', 'Февраль'),
            Yii::t('main', 'Март'),
            Yii::t('main', 'Апрель'),
            Yii::t('main', 'Май'),
            Yii::t('main', 'Июнь'),
            Yii::t('main', 'Июль'),
            Yii::t('main', 'Август'),
            Yii::t('main', 'Сентябрь'),
            Yii::t('main', 'Октябрь'),
            Yii::t('main', 'Ноябрь'),
            Yii::t('main', 'Декабрь'),
        ];
    }

    /**
     * @return string
     */
    protected function getPluginsTemplate()
    {
        return 'year,month,day,label';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::init();

        if (!isset($this->pluginOptions['monthNames'])) {
            $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, ['monthNames' => $this->getMonthNames()]);
        }

        if (!isset($this->pluginOptions['template'])) {
            $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, ['template' => $this->getPluginsTemplate()]);
        }
        if ($this->ageAttribute) {
            $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, [
                'ageAttributeId' => $this->hasModel()
                    ? Html::getInputId($this->model, $this->ageAttribute)
                    : $this->ageAttribute
            ]);
        }

        BirthdayPickerAsset::register($this->view);

        $pluginOptions = Json::encode($this->pluginOptions);

        $this->view->registerJs(sprintf('$("#%s").birthdayPicker(%s)', $this->options['id'], $pluginOptions));

        if ($this->hasModel()) {
            if (isset($this->form)) {
                if ($this->ageAttribute) {
                    return
                        $this->form->field($this->model, $this->ageAttribute, [
                            'options' => ['class' => 'col-1 form-group']
                        ])->textInput(['type' => 'number', 'max' => 120])
                        . $this->form->field($this->model, $this->attribute, [
                            'template' => $this->template,
                            'options' => ['class' => 'col-3 form-group'],
                        ])->textInput($this->options);
                }

                return $this->form->field($this->model, $this->attribute, [
                    'template' => $this->template,
                ])->textInput(['class' => 'selectpicker']);
            }

            if ($this->ageAttribute) {
                return
                    Html::activeTextInput($this->model, $this->ageAttribute, $this->options) .
                    Html::activeTextInput($this->model, $this->attribute, $this->options);
            }

            return Html::activeTextInput($this->model, $this->attribute, $this->options);

        } else {
            if ($this->ageAttribute) {
                return
                    Html::textInput($this->ageAttribute, $this->ageValue, $this->options) .
                    Html::textInput($this->name, $this->value, $this->options);
            }

            return Html::textInput($this->name, $this->value, $this->options);
        }
    }
}
