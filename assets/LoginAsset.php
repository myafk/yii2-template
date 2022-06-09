<?php

namespace app\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $depends = [
        'app\assets\AdminLteAsset',
    ];
}