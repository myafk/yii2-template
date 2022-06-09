<?php

use app\components\helpers\PermissionHelper;
use app\modules\user\models\search\UserSearch;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $searchModel UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('main', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \app\components\grid\AdminGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'username',
        'email:email',
        'fullName',
        'function',
        'phone',
        [
            'attribute' => 'status',
            'value' => 'statusName',
            'filter' => UserSearch::getStatuses()
        ],
        [
            'attribute' => 'role',
            'label' => Yii::t('main', 'Роль'),
            'value' => function($model) {
                return (PermissionHelper::getUserRole($model->id))->description ?? '';
            },
            'filter' => PermissionHelper::listUserRolesByUser()
        ],
        'created_at:daterange',
        [
            'class' => 'app\components\grid\ActionColumn',
            'template' => '{login-by-user} {update}',
            'contentOptions' => ['width' => 90],
            'headerOptions' => ['width' => 90],
            'buttons' => [
                'login-by-user' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('main', 'Войти под пользователем'),
                        'aria-label' => Yii::t('main', 'Войти под пользователем'),
                        'data-pjax' => '0',
                        'data-method' => 'POST',
                    ];
                    return Html::a(
                        Html::tag('span', '', ['class' => "fas fa-sign-in-alt"]),
                        $url,
                        $options
                    );
                },
            ],
        ],
    ],
]); ?>

