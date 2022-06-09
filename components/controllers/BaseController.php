<?php

namespace app\components\controllers;

use app\components\adminlte\AdminLteAlert;
use app\components\helpers\PermissionHelper;
use app\modules\user\models\logs\HistoryLog;
use app\modules\user\models\User;
use Yii;
use yii\base\InlineAction;
use yii\db\ActiveRecordInterface;
use yii\web\Controller;

class BaseController extends Controller
{
    const SESSION_LAST_VISIT = 'last_visit_at';
    const UPDATE_LAST_VISIT_MINUTES = 5;
    const REDIRECT_AFTER_INSERT = 'index';
    const REDIRECT_AFTER_UPDATE = 'index';

    public function setSuccessFlash($message)
    {
        $this->setFlash(AdminLteAlert::TYPE_FLASH_SUCCESS, ['body' => $message]);
    }

    public function setInfoFlash($message)
    {
        $this->setFlash(AdminLteAlert::TYPE_FLASH_WARNING, ['body' => $message]);
    }

    public function setWarningFlash($message)
    {
        $this->setFlash(AdminLteAlert::TYPE_FLASH_WARNING, ['body' => $message]);
    }

    public function setDangerFlash($message)
    {
        $this->setFlash(AdminLteAlert::TYPE_FLASH_DANGER, ['body' => $message]);
    }

    public function setFlash($key, $data = [])
    {
        if ($keyData = Yii::$app->session->get($key)) {
            $keyData[] = $data;
        } else {
            $keyData = [$data];
        }
        Yii::$app->session->setFlash($key, $keyData);
    }

    public function beforeAction($action)
    {
        /** @var InlineAction $action */
        if (!parent::beforeAction($action)) return false;

        if (Yii::$app->user->isGuest) {
            $this->redirect('/');
            return false;
        } else {
            $this->updateUserLastVisit();
        }
        if (!\Yii::$app->user->identity->isActive()) {
            \Yii::$app->user->logout();
        }

        $reflection = new \ReflectionClass($this);
        $controllerClass = lcfirst(str_replace('Controller', '', $reflection->getShortName()));
        $controllerClass = strtolower(preg_replace('/([A-Z])/', '-${1}', $controllerClass));
        $permission = $this->module->id . '/' . $controllerClass . '/' . $action->id;
        if (PermissionHelper::checkPermission($permission, true)) {
            if (Yii::$app->request->isGet && !Yii::$app->request->isAjax) {
                HistoryLog::setLog(Yii::$app->request->getAbsoluteUrl(), 1);
            }
            return true;
        }

        $errorMessage = Yii::t('main', 'Доступ запрещен');
        if (YII_ENV_DEV) {
            $errorMessage .= ' ' . $permission;
        }

        $this->setDangerFlash($errorMessage);
        $this->redirect(Yii::$app->request->referrer ?? Yii::$app->request->hostInfo);
        return false;
    }


    public function renderExt($view, $params = [], $data = [])
    {
        $content = $this->getView()->render($view, $params, $this);
        return $this->renderContentExt($content, $data);
    }

    /**
     * Renders a static string by applying a layout.
     * @param string $content the static string being rendered
     * @param array $data another data for layout
     * @return string the rendering result of the layout with the given static string as the `$content` variable.
     * If the layout is disabled, the string will be returned back.
     * @since 2.0.1
     */
    public function renderContentExt($content, $data)
    {
        $layoutFile = $this->findLayoutFile($this->getView());
        if ($layoutFile !== false) {
            return $this->getView()->renderFile($layoutFile, array_merge(['content' => $content], $data), $this);
        }
        return $content;
    }

    /**
     * Обновление даты последнего посещения у юзера
     */
    protected function updateUserLastVisit()
    {
        if (YII_ENV_DEV) {
            return;
        }

        $now = date("Y-m-d H:i:s");
        $db = Yii::$app->db;
        $lastVisit = Yii::$app->session->get(static::SESSION_LAST_VISIT);
        if (empty($lastVisit) || (time() - strtotime($lastVisit)) > static::UPDATE_LAST_VISIT_MINUTES * 60) {
            $db->createCommand()
                ->update(User::tableName(), ['last_visit_at' => $now], [
                    'id' => Yii::$app->user->id,
                ])->execute();
            Yii::$app->session->set(static::SESSION_LAST_VISIT, $now);
        }
    }

    protected function redirectAfterInsert(ActiveRecordInterface $model)
    {
        if (static::REDIRECT_AFTER_INSERT === 'index') {
            return $this->redirect(['index']);
        }
        return $this->redirect(['update', 'id' => $model->getPrimaryKey()]);
    }

    protected function redirectAfterUpdate(ActiveRecordInterface $model)
    {
        if (static::REDIRECT_AFTER_UPDATE === 'index') {
            return $this->redirect(['index']);
        }
        return $this->redirect(['update', 'id' => $model->getPrimaryKey()]);
    }
}
