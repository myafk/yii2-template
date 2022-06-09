<?php

namespace app\components\widgets;

use app\components\helpers\ArrayHelper;

class Select2 extends \kartik\select2\Select2
{

    public $ajaxUrl;

    public function init()
    {
        parent::init();
        if ($this->ajaxUrl) {
            $this->pluginOptions = ArrayHelper::merge([
                'ajax' => [
                    'url' => $this->ajaxUrl
                ],
                'minimumInputLength' => 3
            ], $this->pluginOptions);
        }
    }

}
