<?php

namespace app\modules\upload\widgets\assets;

use yii\web\AssetBundle;

class AvatarAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/upload/widgets/assets/resources/avatar';

    public $css = [
        'imgarea/imgareaselect-animated.css',
        'css/awesome-avatar.css',
    ];
    public $js = [
        'jquery.imgareaselect.pack.js',
        'awesome-avatar.js',
        'avatar.js',
    ];

    public $depends = [
        'yii\jui\JuiAsset'
    ];
    
}