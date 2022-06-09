<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\settings\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\components\helpers\Select2Helper;
use app\components\widgets\Select2;

$this->title = Yii::t('main', 'Системные настройки');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \app\components\grid\AdminGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'key',
            'value' => function(\app\modules\settings\models\Setting $model) {
                return Select2Helper::format(Select2Helper::FORMAT_SETTING_KEYS, $model->key);
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'key',
                'initValueText' => Select2Helper::format(Select2Helper::FORMAT_SETTING_KEYS, $searchModel->key),
                'ajaxUrl' => \yii\helpers\Url::toRoute('/main/select2/setting-keys'),
            ])
        ],
        [
            'attribute' => 'value',
            'value' => function ($m) {
                return $m->is_json ? Yii::t('main', 'Многопараметный') : $m->value;
            }
        ],
        'comment:ntext',
        [
            'class' => 'app\components\grid\ActionColumn',
            'template' => '{update}'
        ],
    ],
]); ?>

