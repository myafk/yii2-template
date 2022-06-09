<?php

namespace app\modules\user\controllers;

use app\components\helpers\PermissionHelper;
use app\modules\user\models\form\Login;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * User controller
 */
class AuthController extends Controller
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => '@app/views/layouts/error',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }

        $this->layout = '@app/views/layouts/login';

        $model = new Login();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();

        return $this->goHome();
    }

    public function goHome()
    {
        if (PermissionHelper::checkPermission(PermissionHelper::CABINET_DASHBOARD)) {
            return Yii::$app->getResponse()->redirect(['/dashboard']);
        }
        if (PermissionHelper::checkPermission(PermissionHelper::CABINET_PROFILE)) {
            return Yii::$app->getResponse()->redirect(['/profile']);
        }
        return Yii::$app->getResponse()->redirect(['/settings/default/index']);
    }

}
