<?php

namespace app\components\traits;

use yii\web\View;

trait ShortFormAttributes
{

    public function registerShortAttributesJs(string $formId, View $view)
    {
        $view->registerJsVar('shortAttributesMap', $this->toShort());
        $formName = $this->formName();

        $js = <<<JS
            Object.keys(shortAttributesMap).forEach(function(key) {
                let newkey = '$formName' + '[' + key + ']';
                shortAttributesMap[newkey] = '$formName' + '[' + shortAttributesMap[key] + ']';
                delete shortAttributesMap[key];
            });
            $('$formId').on('beforeSubmit', function() {
                $(this).find('[name]').each(function() {
                    if ($(this).attr('multiple') && shortAttributesMap[$(this).attr('name').slice(0, -2)]) {
                        $(this).attr('name', shortAttributesMap[$(this).attr('name').slice(0, -2)] + '[]');
                    } else if (shortAttributesMap[$(this).attr('name')]) {
                        $(this).attr('name', shortAttributesMap[$(this).attr('name')]);
                    }
                });
                return true;
            });
        JS;

        $view->registerJs($js);
    }

    public function toShort(): array
    {
        $shorted = [];
        $alp = 'a';
        $attributes = $this->safeAttributes();
        asort($attributes, SORT_STRING);
        foreach ($attributes as $attribute) {
            $shorted[$attribute] = $alp;
            $alp++;
        }

        return $shorted;
    }

    public function fromShort(array $data, string|null $formName = null): array
    {
        $shortMap = $this->toShort();
        $formName = $formName ?: $this->formName();
        foreach ($shortMap as $attribute => $shortAttribute) {
            if (isset($data[$formName][$shortAttribute])) {
                $data[$formName][$attribute] = $data[$formName][$shortAttribute];
                unset($data[$formName][$shortAttribute]);
            }
        }

        return $data;
    }

    public function load($data, $formName = null)
    {
        $full = $data['full'] ?? false;
        if ($full) {
            $data = $this->fromShort($data, $formName);
        }
        return parent::load($data, $formName);
    }

}
