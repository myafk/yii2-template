<?php

namespace app\modules\upload\widgets;

use app\modules\upload\widgets\assets\AvatarAssets;
use app\modules\upload\models\Attachment;
use Yii;

class AvatarWidget extends UploadWidget
{
    public $success_callback = false;

    protected function getDefaultConfig($id)
    {
        return array_merge([
            'form_id' => 'ava-widget-form-' . $this->attribute,
            'action' => 'ava-image',
            'href_text' => Yii::t('main', 'Нажмите чтобы изменить аватар'),
        ], parent::getDefaultConfig($id));
    }

    public function run()
    {
        $attach = $this->model->{$this->attribute}
            ? Attachment::getAttributeAttachment($this->model->{$this->attribute})
            : new Attachment();

        return $this->render('avatar', [
            'self' => $this,
            'attachment' => $attach
        ]);
    }

    protected function registerAssets()
    {
        $pluginOptions = [
            'id' => '#' . $this->options['id'],
            'attribute' => '#' . $this->attribute,
            'modal' => '#' . $this->attribute . '_modal',
            'img_id' => '#' . $this->options['img_id'],
            'url' => $this->options['url_upload'],
            'success_callback' => $this->success_callback
        ];
        $jsPluginVar = 'avatar_' . hash('crc32', json_encode($pluginOptions));
        $this->getView()->registerJsVar($jsPluginVar, $pluginOptions);
        AvatarAssets::register($this->view);

        $jsCode = "initAvatar($jsPluginVar)";
        Yii::$app->view->registerJs($jsCode, \yii\web\View::POS_READY);
    }

}
