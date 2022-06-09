<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AjaxModalAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/resources/ajax-modal';
    
    //public $css = ['modal.css'];
    public $js = ['modal.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\CommonAsset',
    ];
    
}
