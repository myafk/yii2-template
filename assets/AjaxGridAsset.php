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
class AjaxGridAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/resources/ajax-grid';

    public $js = ['grid-modal.js'];
    public $depends = [
        'app\assets\AdminLteAsset',
        'app\assets\AjaxModalAsset',
    ];
    
}
