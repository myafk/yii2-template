<?php

namespace app\modules\upload\controllers;

use app\components\controllers\BaseController;
use app\modules\upload\models\Attachment;
use app\components\helpers\RandomHelper;
use app\modules\upload\models\form\AttachmentTitleForm;
use Imagick;
use yii;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;

class DefaultController extends BaseController
{

    public $enableCsrfValidation = false;

    protected $whiteList = [
        'jpg', 'jpeg', 'png', 'gif',
        'docx', 'doc', 'xlsx', 'xls', 'txt', 'rtf', 'pdf',
    ];

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionUpload()
    {
        $uploads = new Attachment();

        if (isset($_FILES['files'])) {
            $modelClass = Yii::$app->request->post('model_class', 'common');

            $uploadedFile = UploadedFile::getInstanceByName('files');
            if ($uploadedFile == NULL) {
                return ['error' => 'Файл не загружен'];
            }

            if (!in_array(strtolower($uploadedFile->extension), $this->whiteList)) {
                return ['error' => 'Файл не поддерживается'];
            }

            $uploads->model = strtolower($modelClass);
            $uploads->model_attribute = Yii::$app->request->post('model_attribute');
            $uploads->object_id = Yii::$app->request->post('model_id');
            $uploads->path = $fileName = time() . '-' . RandomHelper::getRnd() . '.' . $uploadedFile->extension;
            $uploads->mime = mime_content_type($uploadedFile->tempName);

            if (!$uploads->save()) {
                return ['error' => $uploads->getErrors()];
            }

            if (!empty($uploadedFile)) {
                $dir = Attachment::mainUploadPath() . strtolower($modelClass);
                try {
                    FileHelper::createDirectory($dir);
                } catch (\Exception $e) {
                    return ['error' => 'Ошибка сервера. Обратитесь к администратору #1'];
                }

                $imgPath = $dir . '/' . $fileName;

                if ($uploadedFile->saveAs($imgPath)) {
                    $thumb = false;
                    if (is_array(getimagesize($imgPath))) {
                        try {
                            $imagickOrigin = new Imagick($imgPath);
                        } catch (\Exception $e) {
                            return ['error' => 'Ошибка сервера. Обратитесь к администратору #3'];
                        }

                        if (Yii::$app->request->post('image-title')) {
                            $this->titleImage($dir, $fileName, $imagickOrigin);
                            $thumb = $uploads->getTitleUrl();
                        }

                        if (Yii::$app->request->post('image-thumb')) {
                            $this->thumbImage($dir, $fileName, $imagickOrigin);
                            $thumb = $uploads->getThumbUrl();
                        }

                        if (Yii::$app->request->post('image-watermark')) {
                            $this->addWatermark($imagickOrigin);
                        }
                    }

                    $info = [];
                    $info["url"] = $uploads->getUrl();
                    $info["thumbUrl"] = $thumb;
                    $info["name"] = $fileName;
                    $info["type"] = $uploads->mime;
                    $info["size"] = filesize($imgPath);
                    $info["deleteUrl"] = "/uploads/default/delete/$uploads->id";
                    $info["deleteType"] = "DELETE";
                    $info["id"] = intval($uploads->id);
                    return ['files' => $info];
                } else {
                    return ['error' => 'Ошибка сервера. Обратитесь к администратору #2'];
                }
            }
        }

        return ['error' => 'Нет загруженных файлов'];
    }

    public function actionDelete() // TODO Настроить права доступа
    {
        $id = Yii::$app->request->post('id', 0);
        /* Удалять можно только свои или общие младше часа */
        /** @var Attachment $upload */
        $upload = Attachment::find()->where(['id' => $id])->one();
        if (!$upload) {
            return ['status' => false, 'message' => 'Not found'];
        }
        if (($upload->user_id && $upload->user_id == Yii::$app->user->id)
            || (!$upload->user_id && strtotime($upload->created_at) > (time() - 60 * 60))
        ) {
            $upload->delete();
            return ['status' => true];
        }

        return ['status' => false];
    }

    public function actionUpdate()
    {
        $id = Yii::$app->request->post('id', 0);

        $upload = AttachmentTitleForm::find()->where(['id' => $id])->one();
        if (!$upload) {
            return ['status' => false, 'message' => 'Not found'];
        }

        if ($upload->load(Yii::$app->request->post(), '') && $upload->save()) {
            return ['status' => true];
        }
        return ['status' => false];
    }

    /**
     * @param Imagick|string $originalImage
     * @return Imagick|false
     * @throws \ImagickException
     */
    protected function addWatermark($originalImage)
    {
        if (!($originalImage instanceof Imagick)) {
            $imgPath = $originalImage;
            $originalImage = new Imagick();
            $originalImage->readImage($imgPath);
        }
        if (!file_exists($waterPath = Yii::getAlias('@webPath') . '/images/water.png')) {
            return false;
        }

        $image_x = $originalImage->getImageWidth();
        $image_y = $originalImage->getImageHeight();

        $watermark = new Imagick();
        $watermark->readImage($waterPath);
        $watermark_width = $watermark->getImageWidth();
        $watermark_height = $watermark->getImageHeight();
        if ($image_x < $watermark_width) {
            $watermark->scaleImage($image_x, $watermark->getImageHeight());
        }
        if ($image_y < $watermark_height) {
            $watermark->scaleImage($watermark->getImageWidth(), $image_y);
        }

        $originalImage->compositeImage($watermark, Imagick::COMPOSITE_OVER, 0, 0);
        $originalImage->writeImage();
        return $originalImage;
    }

    /**
     * @param string $dir
     * @param string $fileName
     * @param Imagick $image
     * @return Imagick
     * @throws \ImagickException|\Exception
     */
    protected function thumbImage($dir, $fileName, $image)
    {
        $thumbDir = $dir . '/thumbs/';
        FileHelper::createDirectory($thumbDir);

        $thumbImage = clone $image;
        $thumbImage->cropThumbnailImage(100, 100);
        $thumbImage->setCompressionQuality(65);
        $thumbImage->writeImage($thumbDir . $fileName);
        return $thumbImage;
    }

    /**
     * @param string $dir
     * @param string $fileName
     * @param Imagick $image
     * @return Imagick
     * @throws \ImagickException|\Exception
     */
    protected function titleImage($dir, $fileName, $image)
    {
        $thumbDir = $dir . '/title/';
        FileHelper::createDirectory($thumbDir);

        $thumbImage = clone $image;
        $this->cropImage($thumbImage);
        $thumbImage->writeImage($thumbDir . $fileName);
        return $thumbImage;
    }

    /**
     * @param Imagick|string $originalImage
     * @return Imagick
     * @throws \ImagickException
     */
    protected function cropImage($originalImage)
    {
        if (!($originalImage instanceof Imagick)) {
            $imgPath = $originalImage;
            $originalImage = new Imagick();
            $originalImage->readImage($imgPath);
        }

        if ($ratio = Yii::$app->request->post('image-ratio', false)) {
            $x1 = Yii::$app->request->post('image-x1', 0) * $ratio;
            $x2 = Yii::$app->request->post('image-x2', 0) * $ratio;
            $y1 = Yii::$app->request->post('image-y1', 0) * $ratio;
            $y2 = Yii::$app->request->post('image-y2', 0) * $ratio;
            $width = $x2 - $x1;
            $height = $y2 - $y1;
            $originalImage->cropImage($width, $height, $x1, $y1);
        } else {
            $size_x = $originalImage->getImageWidth();
            $size_y = $originalImage->getImageHeight();

            if ($size_x > 800 && $size_y > 600) {
                $coefficient = (float)($size_x) / (float)($size_y);
                if ($coefficient > 1.33) {
                    $image_y = (int)(($size_y / $size_x) * 800);
                    $originalImage->resizeImage(800, $image_y, Imagick::FILTER_LANCZOS, 1);
                } else {
                    $image_x = (int)($coefficient * 600);
                    $originalImage->resizeImage($image_x, 600, Imagick::FILTER_LANCZOS, 1);
                }
            }
        }

        return $originalImage;
    }

}
