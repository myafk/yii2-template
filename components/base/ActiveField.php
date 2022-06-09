<?php

namespace app\components\base;

use app\components\widgets\Select2;
use yii\helpers\Html;

class ActiveField extends \yii\bootstrap4\ActiveField
{

    public function select2List($items = [], $options = []): ActiveField
    {
        return $this->widget(Select2::class, array_merge_recursive([
            'data' => $items
        ], $options));
    }

}
