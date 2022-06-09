<?php

namespace app\components\widgets;

use yii\helpers\ArrayHelper;
use app\components\helpers\DateHelper;
use yii\web\JsExpression;
use Yii;

class DateRangePicker extends \kartik\daterange\DateRangePicker
{

    public $presetDropdown = true;
    public $convertFormat = true;

    public function init()
    {
        parent::init();

        $this->pluginOptions = array_merge_recursive([
            'locale' => ['format' => DateHelper::FORMAT_DATE],
            'ranges' => DateHelper::getJsExpressionPeriods(),
        ], $this->pluginOptions);
    }

    public function run()
    {
        parent::run();

        $this->initFixEmpty();
    }

    protected function initFixEmpty()
    {
        $rangeAttrId = "$('#{$this->options['id']}')";
        $datePicker = $this->presetDropdown ? '$(this).find(".kv-drp-dropdown")' : $rangeAttrId;
        $input = $this->presetDropdown ? "$datePicker.find('input')" : $rangeAttrId;

        $js = <<<JS
$(function () {
    $rangeAttrId.parent().click(function () {
        if (!$rangeAttrId.val()) {
            let data = $datePicker.data();
            $input.val(data.daterangepicker.startDate.format(data.locale.format) + ' - ' + data.daterangepicker.endDate.format(data.locale.format));
        }
    });
});
JS;
        $this->view->registerJs($js);
    }

    protected function initLocale()
    {
        $parent = new \ReflectionObject($this);
        $assetDir = dirname($parent->getParentClass()->getFileName()) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        $this->setLanguage('', $assetDir);
        if (empty($this->_langFile)) {
            return;
        }
        $localeSettings = ArrayHelper::getValue($this->pluginOptions, 'locale', []);
        $localeSettings += [
            'applyLabel' => Yii::t('kvdrp', 'Apply'),
            'cancelLabel' => Yii::t('kvdrp', 'Cancel'),
            'fromLabel' => Yii::t('kvdrp', 'From'),
            'toLabel' => Yii::t('kvdrp', 'To'),
            'weekLabel' => Yii::t('kvdrp', 'W'),
            'customRangeLabel' => Yii::t('kvdrp', 'Custom Range'),
            'daysOfWeek' => new JsExpression('moment.weekdaysMin()'),
            'monthNames' => new JsExpression('moment.monthsShort()'),
            'firstDay' => new JsExpression('moment.localeData()._week.dow'),
        ];
        $this->pluginOptions['locale'] = $localeSettings;
    }

}
