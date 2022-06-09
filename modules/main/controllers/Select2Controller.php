<?php

namespace app\modules\main\controllers;

use app\components\controllers\BaseController;
use app\components\helpers\Select2Helper;
use app\modules\main\models\Candidate;
use app\modules\main\models\CandidateTrainingGroupDepartmentItem;
use app\modules\settings\models\Setting;
use app\modules\user\models\User;
use yii\db\Query;
use yii\web\Response;

class Select2Controller extends BaseController
{

    public function init()
    {
        parent::init();
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionSettingKeys(string $q): array
    {
        return $this->simpleAction(Setting::find(), Select2Helper::FORMAT_SETTING_KEYS, $q, key: 'key');
    }

    public function actionUsers(string $q, string|null $role = null): array
    {
        $query = User::find();
        if ($role) {
            $query->leftJoin('auth_assignment auth', 'auth.user_id = users.id');
            $query->andWhere(['auth.item_name' => $role]);
        }
        return $this->simpleAction($query, Select2Helper::FORMAT_USERS, $q, ['first_name', 'last_name', 'patronymic', 'username']);
    }

    protected function simpleAction(Query $query, string $format, ?string $q = null, ?array $vars = [], ?string $key = 'id'): array
    {
        $out = ['results' => ['id' => '', 'text' => '']];

        if ($q) {
            if (empty($vars)) {
                $vars = Select2Helper::getVariablesFromFormat($format);
            }
            $or = [];
            foreach ($vars as $var) {
                $or[] = ['like', $var, $q];
            }
            $query->andWhere(['or', ...$or]);
            $out['results'] = Select2Helper::formatArray($format, $query->all(), 'id', $key);
        }

        return $out;
    }

}
