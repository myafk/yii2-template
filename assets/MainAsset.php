<?php

namespace app\assets;

use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    public $depends = [
        'app\assets\AdminLteAsset',
        'app\assets\AdminLteDatepickerAsset',
        'app\assets\AppAsset',
    ];
}
