<?php

namespace app\components\widgets\assets;

use yii\web\AssetBundle;

class BirthdayPickerAsset extends AssetBundle
{
    public $sourcePath = '@app/components/widgets/assets/birthday-picker';

    public $js = [
        'jquery.birthdaypicker.js'
    ];

    public $css = [
        'jquery.birthdaypicker.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

}
