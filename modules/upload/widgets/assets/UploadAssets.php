<?php

namespace app\modules\upload\widgets\assets;

use yii\web\AssetBundle;

class UploadAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/upload/widgets/assets/resources/upload';

    public $js = [
        'jquery.fileupload.js',
        'upload.js',
    ];

    public $depends = [
        'yii\jui\JuiAsset'
    ];
}