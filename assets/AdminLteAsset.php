<?php

namespace app\assets;

use yii\base\Exception;
use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte';

    public $css = [
        'plugins/fontawesome-free/css/all.min.css',
        'plugins/daterangepicker/daterangepicker.css',
        'plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
        'plugins/select2/css/select2.min.css',
        'plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
        'dist/css/adminlte.min.css',
        'plugins/ekko-lightbox/ekko-lightbox.css',
    ];

    public $js = [
        'plugins/select2/js/select2.full.min.js',
        'plugins/moment/moment.min.js',
        'plugins/daterangepicker/daterangepicker.js',
        'plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
        'dist/js/adminlte.min.js',
        'plugins/ekko-lightbox/ekko-lightbox.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\CommonAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'lajax\translatemanager\bundles\TranslationPluginAsset'
    ];

    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = false; //'_all-skins';

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }

        parent::init();
    }
}
