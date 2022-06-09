<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $baseUrl = '@web';

    public $css = [
        'resource/css/reset.css',
        'resource/css/style.css',
    ];
    public $js = [
        'resource/js/reset.js',
    ];

    public $depends = [
        'app\assets\AdminLteAsset',
        'app\assets\UrlfyAsset',
    ];
}
