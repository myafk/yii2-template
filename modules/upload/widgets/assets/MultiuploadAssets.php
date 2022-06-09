<?php

namespace app\modules\upload\widgets\assets;

use yii\web\AssetBundle;

class MultiuploadAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/upload/widgets/assets/resources/multiupload';

    public $css = [
        'multiupload-theme-default.css',
    ];
    public $js = [
        'multiupload.js'
    ];

    public $depends = [
        'app\modules\upload\widgets\assets\UploadAssets',
    ];
}