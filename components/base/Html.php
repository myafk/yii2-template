<?php

namespace yii\helpers;

use app\components\widgets\Select2;
use Yii;

class Html extends \yii\helpers\BaseHtml
{

    public static function activeDropDownList($model, $attribute, $items, $options = [])
    {
        $options = array_merge(['prompt' => Yii::t('main', '- Выберите -')], $options);
        return parent::activeDropDownList($model, $attribute, $items, $options);
    }

    public static function errorSummaryForm($errors, $options = [])
    {
        $header = isset($options['header']) ? $options['header'] : '<p>' . Yii::t('yii', 'Please fix the following errors:') . '</p>';
        $footer = ArrayHelper::remove($options, 'footer', '');
        unset($options['header']);
        if (empty($errors)) {
            $content = '<ul></ul>';
            $options['style'] = isset($options['style']) ? rtrim($options['style'], ';') . '; display:none' : 'display:none';
        } else {
            $lines = [];
            foreach ($errors as $error) {
                foreach ($error as $value) {
                    $lines[] = $value;
                }
            }
            $content = '<ul><li>' . implode("</li>\n<li>", $lines) . '</li></ul>';
        }

        return Html::tag('div', $header . $content . $footer, $options);
    }

}
