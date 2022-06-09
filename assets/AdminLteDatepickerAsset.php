<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminLteDatepickerAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/resources/datepicker';

    public $js = [
        'datepicker.js',
    ];

    public $depends = [
        'app\assets\AdminLteAsset',
    ];
}