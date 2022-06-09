<?php

namespace app\modules\profile\controllers;

use app\components\controllers\BaseController;
use app\modules\profile\models\form\AvatarForm;
use app\modules\profile\models\form\ChangePassword;
use app\modules\profile\models\form\DebugForm;
use app\modules\profile\models\form\ProfileForm;
use Yii;
use yii\bootstrap4\Html;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Default controller for the `profile` module
 */
class DefaultController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'profile' => ['post'],
                    'change-password' => ['post'],
                    'avatar' => ['post'],
                    'debug' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProfile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new ProfileForm();

        if ($form->load(Yii::$app->request->post()) && $form->change()) {
            return [
                'status' => true,
                'message' => Yii::t('main', 'Данные успешно сохранены'),
            ];
        }
        return [
            'status' => false,
            'errorSummary' => Html::errorSummary($form)
        ];
    }

    public function actionChangePassword()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new ChangePassword();

        if ($form->load(Yii::$app->request->post()) && $form->change()) {
            return [
                'status' => true,
                'message' => Yii::t('main', 'Пароль успешно изменен'),
            ];
        }
        return [
            'status' => false,
            'errorSummary' => Html::errorSummary($form)
        ];
    }

    public function actionAvatar()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new AvatarForm();

        if ($form->load(Yii::$app->request->post(), '') && $form->change()) {
            return [
                'status' => true,
                'message' => Yii::t('main', 'Аватар изменен'),
            ];
        }
        return [
            'status' => false,
            'errorSummary' => Html::errorSummary($form)
        ];
    }

    public function actionDebug()
    {
        $form = new DebugForm();

        if ($form->load(Yii::$app->request->post()) && $form->change()) {
            $this->setSuccessFlash(Yii::t('main', 'Настройки отладки изменены, перезагрузите страницу'));
            return $this->redirect(Yii::$app->request->referrer ?? ['index']);
        }
        $this->setDangerFlash(Yii::t('main', 'Ошибка. Настройки отладки не изменены'));
        return $this->redirect(Yii::$app->request->referrer ?? ['index']);
    }
}
