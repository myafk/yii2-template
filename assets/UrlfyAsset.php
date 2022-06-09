<?php

namespace app\assets;

use yii\web\AssetBundle;

class UrlfyAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/resources/urlfy';

    public $js = [
        'urlify.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}