<?php

/**
 *  echo $form->field($model, 'cid')->widget(UploadWidget::className());
 *
 *  echo $form->field($model, 'cid')->widget(UploadWidget::className(), [ 'options' => [
 *      'id'        - (string) id input hidden field
 *      'name'      - (string) name input hidden field
 *      'file_button'   - (array) if isset use button [
 *          'button_text'   - (string) if use button, use class
 *          'button_class'  - (string) if use button, use class
 *      ]
 *      'progress_bar'  - (array) htmlOptions
 *
 *      'file_id'       - (string) id file input
 *
 *
 *      'width_img'  - (string) width img
 *      'height_img'  - (string) height img
 *
 *      'url_upload'- (string) url for ajax function
 *      'ajax_options'   - (array) POST parametrs, which send on server by ajax
 *
 *      'div_class' - (string) class block of images
 *      ]
 *  ]);
 */

namespace app\modules\upload\widgets;

use app\modules\upload\widgets\assets\MultiuploadAssets;
use app\modules\upload\models\Attachment;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


class MultiuploadWidget extends UploadWidget
{
    const MAX_NUMBER_OF_FILES = 25;
    const DIV_CLASS = 'muw-block';

    public $viewVariant = 'multi';
    public $attachDataUniqId;
    public $attachments;

    public function init()
    {
        $this->attachDataUniqId = 'attach_data_' . uniqid();
        $options = [
            'div_class' => self::DIV_CLASS,
            'delete_url' => Url::to(['/upload/default/delete']),
            'update_url' => Url::to(['/upload/default/update']),
            'progress_bar' => [
                'id' => 'progress-' . $this->attribute,
                'class' => 'progress',
                'style' => 'width: 100%;margin: 6px 10px;',
            ]
        ];
        $this->options = ArrayHelper::merge($options, $this->options);

        $this->model instanceof ActiveRecord
            ? $this->attachments = Attachment::getModelAttachments($this->model, $this->attribute)
            : $this->attachments = Attachment::getAttachmentsByIds($this->model->{$this->attribute});

        parent::init();
    }

    public function run()
    {
        return $this->render($this->viewVariant, [
            'widget' => $this,
            'options' => $this->options,
        ]);
    }

    public function loadFileInput()
    {
        $fileButtonId = $this->options['file_id'] . self::ID_FILE_BUTTON;
        $this->registerButtonJs($fileButtonId);

        $fileInputOptions = [
            'id' => $this->options['file_id'],
            'style' => 'display:none',
            'multiple' => true,
        ];

        return Html::fileInput('files', '', $fileInputOptions)
            . Html::button('Добавить', [
            'id' => $fileButtonId,
            'class' => 'btn btn-primary',
        ]);
    }

    protected function registerAssets()
    {
        $this->getView()->registerJsVar('filesCounter', 0);

        $attachmentData = [];
        foreach ($this->attachments as $attach) {
            $attachmentData[] = [
                'id' => $attach->id,
                'img_url' => $attach->getUrl(),
                'img_thumb' => $attach->getThumbUrl(),
                'input_title' => $attach->title,
                'input_description' => $attach->description,
                'input_sort' => $attach->sort,
            ];
        }
        $jsPluginDataVarName = 'multiupload_data_' . hash('crc32', json_encode($attachmentData));
        $this->getView()->registerJsVar($jsPluginDataVarName, $attachmentData);

        $pluginOptions = [
            'id' => '#' . $this->options['id'],
            'file_id' => '#' . $this->options['file_id'],
            'img_id' => '#' . $this->options['img_id'],
            'url_upload' => $this->options['url_upload'],
            'delete_url' => $this->options['delete_url'],
            'update_url' => $this->options['update_url'],
            'max_filesize' => self::MAX_FILESIZE,
            'application_image' => self::APPLICATION_IMAGE,
            'file_validate_type' => $this->options['file_validate_type'],
            'file_regex_type' => $this->options['file_regex_type'],
            'progress_bar_id' => '#' . $this->options['progress_bar']['id'],
            'width_img' => $this->options['width_img'],
            'height_img' => $this->options['height_img'],
            'link' => $this->with_links ? 1 : 0,
            'formData' => $this->loadFormData(),
            'maxFiles' => self::MAX_NUMBER_OF_FILES,
        ];

        $jsPluginOptionsVarName = 'multiupload_' . hash('crc32', json_encode($pluginOptions));
        $this->getView()->registerJsVar($jsPluginOptionsVarName, $pluginOptions);

        MultiuploadAssets::register($this->view);

        $jsCode = "var multiWidget = new Multiupload($jsPluginOptionsVarName, $jsPluginDataVarName)";
        Yii::$app->view->registerJs($jsCode, \yii\web\View::POS_READY);
    }

    private function registerButtonJs($button_id)
    {
        $jsCode = "
            $('#{$button_id}').on('click', function(){
                $('#{$this->options['file_id']}').trigger('click');
                return false;
            });
        ";
        Yii::$app->view->registerJs($jsCode, \yii\web\View::POS_READY);
    }
}