<?php

namespace app\components\grid;

use Yii;
use yii\base\Model;
use yii\bootstrap4\Html;

class DataColumn extends \yii\grid\DataColumn
{
    /**
     * @inheritdoc
     */
    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;

        if ($this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            $error = $this->renderFilterCellErrorContent($model);
            if (is_array($this->filter)) {
                $options = array_merge(['prompt' => ' - '], $this->filterInputOptions);
                Html::addCssClass($options, 'alte-select2');
                return Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . $error;
            } else {
                $options = array_merge(['prompt' => ''], $this->filterInputOptions);
                switch ($this->format) {
                    case 'boolean':
                    case 'check':
                        return Html::activeDropDownList($model, $this->attribute, [
                                '1' => Yii::t('main', 'Вкл'),
                                '0' => Yii::t('main', 'Выкл')
                            ], $options) . $error;
                    case 'date':
                        return $this->renderInputGroup(
                            Html::activeTextInput($model, $this->attribute, [
                                'class' => 'form-control float-right dp-lte-date'
                            ])
                        );
                    case 'datetime':
                        return $this->renderInputGroup(
                            Html::activeTextInput($model, $this->attribute, [
                                'class' => 'form-control float-right dp-lte-datetime'
                            ])
                            , 'fa-clock');
                    case 'daterange':
                        return $this->renderInputGroup(
                            Html::activeTextInput($model, $this->attribute, [
                                'class' => 'form-control float-right dpr-lte-date'
                            ])
                        );
                    case 'datetimerange':
                        return $this->renderInputGroup(
                            Html::activeTextInput($model, $this->attribute, [
                                'class' => 'form-control float-right dpr-lte-datetime'
                            ])
                            , 'fa-clock');
                }
            }
            return Html::activeTextInput($model, $this->attribute, $this->filterInputOptions) . $error;

        } else {
            return parent::renderFilterCellContent();
        }
    }

    protected function renderInputGroup($input, $fa = 'fa-calendar-alt')
    {
        return Html::tag('div',
            Html::tag('div',
                Html::tag('span',
                    '<i class="far ' . $fa . '"></i>',
                    ['class' => 'input-group-text']
                ),
                ['class' => 'input-group-prepend']
            ) .
            $input,
            ['class' => 'input-group']
        );
    }

    /**
     * @param \yii\base\Model $model
     * @return string
     */
    protected function renderFilterCellErrorContent($model)
    {
        if ($model->hasErrors($this->attribute)) {
            Html::addCssClass($this->filterOptions, 'has-error');
            $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
        } else {
            $error = '';
        }
        return $error;
    }
}