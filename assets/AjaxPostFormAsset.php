<?php

namespace app\assets;

use yii\web\AssetBundle;

class AjaxPostFormAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/resources/ajax-post-form';

    public $js = [
        'ajax-post-form.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\AdminLteAlertAsset',
        'lajax\translatemanager\bundles\TranslationPluginAsset'
    ];
}