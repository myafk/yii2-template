<?php
/**
 *  echo $form->field($model, 'cid')->widget(UploadWidget::className());
 *
 *  echo $form->field($model, 'cid')->widget(UploadWidget::className(), [
 *  'with_links'        - (bool) if TRUE set link on image
 *  'options' => [
 *      'id'            - (string) id input hidden field
 *      'name'          - (string) name input hidden field
 *      'file_button'   - (array) if isset use button [
 *          'button_text'   - (string) if use button, use class
 *          'button_class'  - (string) if use button, use class
 *      ]
 *      'file_id'       - (string) id file input
 *      'img_none'      - (string) if TRUE no display img
 *      'img_id'        - (string) id img
 *      'alt_img'       - (string) alt img
 *      'width_img'     - (string) width img
 *
 *      'attach_to_button' - (string) ID of button, that will trigger fileupload
 *
 *      'url_upload'    - (string) url for ajax function
 *      'ajax_options'  - (array) POST parametrs, which send on server by ajax
 *      ]
 *  ]);
 */

namespace app\modules\upload\widgets;

use app\modules\upload\components\UploadHelper;
use app\modules\upload\widgets\assets\UploadAssets;
use app\modules\upload\models\Attachment;
use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

class UploadWidget extends Widget
{
    const MAX_FILESIZE = 1024 * 1024 * 10;

    const ID_FILE_INPUT = '-file';
    const ID_FILE_BUTTON = '-button';
    const ID_IMG = '-img';
    const WIDTH_IMG = '100px';
    const HEIGHT_IMG = '100px';
    const URL_UPLOAD = '/upload/default/upload';
    const APPLICATION_IMAGE = '/images/icons/text-file-icon.png';
    const ALT_IMG = '/images/icons/text-file-icon.png';

    /** @var ActiveRecord */
    public $model;

    public $attribute;

    public $options = [];

    public $with_links = true;

    public function init()
    {
        if (!$this->hasModel()) {
            throw new InvalidConfigException("'model' and 'attribute' properties must be specified.");
        }
        $id = $this->options['id'] ?? ($this->hasModel()
                ? Html::getInputId($this->model, $this->attribute)
                : $this->getId()
            );
        $this->options = ArrayHelper::merge($this->getDefaultConfig($id), $this->options);

        if (isset($this->options['file_regex_type'])) {
            $this->options['file_validate_type'] = 1;
        } else {
            $this->options['file_validate_type'] = 0;
            $this->options['file_regex_type'] = '';
        }

        $this->registerAssets();

        parent::init();
    }

    protected function getDefaultConfig($id)
    {
        return [
            'id' => $id,
            'name' => $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->getId(),
            'file_id' => $id . self::ID_FILE_INPUT,
            'img_id' => $id . self::ID_IMG,
            'url_upload' => Url::to([self::URL_UPLOAD]),
            'width_img' => self::WIDTH_IMG,
            'height_img' => self::HEIGHT_IMG,
        ];
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }

    public function run()
    {
        /** @var Attachment $attach */
        $attach = Attachment::find()->where(['id' => $this->model->{$this->attribute}])->one();

        if (!(isset($this->options['img_none']) && $this->options['img_none'] === true)) {
            if ($attach) {
                $url = $attach ? $attach->url : false;
                $thumbUrl = $attach->isTypeImage() ? $url : self::APPLICATION_IMAGE;
            } else {
                $url = $thumbUrl = false;
            }
            $image = Html::img($thumbUrl, [
                'id' => $this->options['img_id'],
                'alt' => isset($this->options['alt_img']) ? $this->options['alt_img'] : self::ALT_IMG,
                'width' => $this->options['width_img'],
                'class' => 'upload-image',
                'style' => $attach ? '' : 'display: none',
            ]);

            if ($this->with_links) {
                echo Html::a($image, $url, ['target' => '_blank']);
            } else {
                echo $image;
            }
        }

        $this->loadFileInput();

        echo Html::hiddenInput($this->options['name'], $this->model->{$this->attribute}, [
            'id' => $this->options['id'],
        ]);

    }

    public function loadFileInput()
    {
        if (isset($this->options['file_button'])) {
            if (!isset($this->options['file_button']['button_text']))
                $this->options['file_button']['button_text'] = 'Добавить файл';
            if (!isset($this->options['file_button']['button_id']))
                $this->options['file_button']['button_id'] = $this->options['file_id'] . self::ID_FILE_BUTTON;
            if (!isset($this->options['file_button']['button_class']))
                $this->options['file_button']['button_class'] = 'btn';
        }

        if (!isset($this->options['file_button'])) {
            if (!isset($this->options['attach_to_button'])) {
                echo Html::fileInput('files', '', [
                    'id' => $this->options['file_id'],
                    'multiple' => true
                ]);
            } else {
                echo Html::fileInput('files', '', [
                    'id' => $this->options['file_id'],
                    'multiple' => true,
                    'style' => 'display: none'
                ]);
                $this->registerButtonJs($this->options['attach_to_button']);
            }
        } else {
            $fileInputOptions = [];
            $fileInputOptions['id'] = $this->options['file_id'];
            $fileInputOptions['style'] = 'display:none';
            if ($this instanceof MultiuploadWidget) {
                $fileInputOptions['multiple'] = true;
            }

            echo Html::fileInput('files', '', $fileInputOptions);
            echo Html::button($this->options['file_button']['button_text'], [
                'id' => $this->options['file_button']['button_id'],
                'class' => $this->options['file_button']['button_class'],
            ]);
            $this->registerButtonJs($this->options['file_button']['button_id']);
        }
    }

    protected function loadFormData()
    {
        $options = [
            'model_class' => UploadHelper::getClassName($this->model),
            'model_attribute' => $this->attribute
        ];
        if ($this->model->hasMethod('getPrimaryKey')
            && ($model_id = $this->model->getPrimaryKey()) !== NULL
        ) {
            $options['model_id'] = $model_id;
        }

        $this->options['ajax_options'] = ArrayHelper::merge($options, $this->options['ajax_options'] ?? []);

        return $this->options['ajax_options'];
    }

    protected function registerAssets()
    {
        $pluginOptions = [
            'id' => '#' . $this->options['id'],
            'file_id' => '#' . $this->options['file_id'],
            'img_id' => '#' . $this->options['img_id'],
            'url_upload' => $this->options['url_upload'],
            'max_filesize' => self::MAX_FILESIZE,
            'application_image' => self::APPLICATION_IMAGE,
            'file_validate_type' => $this->options['file_validate_type'],
            'file_regex_type' => $this->options['file_regex_type'],
            'formData' => $this->loadFormData(),
        ];
        $jsPluginVar = 'multiupload_' . hash('crc32', json_encode($pluginOptions));
        $this->getView()->registerJsVar($jsPluginVar, $pluginOptions);

        UploadAssets::register($this->view);

        $jsCode = "initUpload($jsPluginVar)";
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