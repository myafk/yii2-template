<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminLteAlertAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/resources/alert';

    public $js = [
        'common-alert.js',
    ];

    public $depends = [
        'app\assets\AdminLteAsset',
    ];
}