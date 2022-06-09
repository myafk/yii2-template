<?php

namespace app\modules\user\controllers;

use app\components\controllers\BaseController;
use app\components\helpers\PermissionHelper as PH;
use app\modules\log\models\ServerLog;
use app\modules\user\models\form\UserCreateForm;
use app\modules\user\models\form\UserUpdateForm;
use app\modules\user\models\User;
use app\modules\user\models\search\UserSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
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
                    'login-by-user' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserCreateForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('main', 'Успешно сохранено'));

            return $this->redirectAfterInsert($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (($model = UserUpdateForm::findOne($id)) === null) {
            throw new NotFoundHttpException(Yii::t('main', 'The requested page does not exist.'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('main', 'Успешно сохранено'));

            return $this->redirectAfterUpdate($model);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Log in an existing User model.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionLoginByUser($id)
    {
        // Проверка статуса пользователя, если статус не активен, то выдадим сообщение что залогиниться нельзя.
        $user = $this->findModel($id);
        if (!$user->isActive()) {
            $this->setDangerFlash(Yii::t('main', 'Пользователь не активен'));
            return $this->redirect(['index']);
        }
        // Проверка ролей пользователя, если ролей нет, то выдадим сообщение что залогиниться нельзя.
        if (empty(array_keys(Yii::$app->authManager->getRolesByUser($user->id)))) {
            $this->setDangerFlash(Yii::t('main', 'Пользователь не имеет ролей, сначала укажите роль пользователя'));
            return $this->redirect(['index']);
        }
        if (in_array(PH::ROLE_ROOT, array_keys(Yii::$app->authManager->getRolesByUser($user->id)))) {
            $this->setDangerFlash(Yii::t('main', 'Нельзя войти под пользователя с ролью root'));
            return $this->redirect(['index']);
        }

        $backIdentity = Yii::$app->user->identity;
        ServerLog::info($this, 'login-by-user', [
            'userFrom' => Yii::$app->user->id,
            'userTo' => $user->primaryKey
        ]);
        Yii::$app->user->switchIdentity($user, 0);
        Yii::$app->session->set(User::SESSION_AUTH_TOKEN_KEY, $user->getAuthKey());
        Yii::$app->session->set(User::SESSION_BACK_IDENTITY_ID, $backIdentity->getId());

        return $this->redirect(['/']);
    }

    public function actionLogoutByUser()
    {
        if ($id = Yii::$app->session->get(User::SESSION_BACK_IDENTITY_ID)) {
            $user = $this->findModel($id);
            Yii::$app->user->switchIdentity($user, 0);
            Yii::$app->session->remove(User::SESSION_BACK_IDENTITY_ID);
            Yii::$app->session->set(User::SESSION_AUTH_TOKEN_KEY, $user->getAuthKey());
        }
        return $this->redirect(['/']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('main', 'The requested page does not exist.'));
    }
}
