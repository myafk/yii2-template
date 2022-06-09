<?php

namespace app\components\adminlte\widgets;

use yii\widgets\ActiveForm;

class AdminLteActiveForm extends ActiveForm
{
    public $fieldConfig = [
        'template' => '<div class="row">
            <div class="col-3 text-right">{label}</div>
            <div class="col-9">{input}</div>
            <div class="col-12">{error}</div>
        </div><hr>',
    ];
}