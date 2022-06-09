<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\upload\models\search\AttachmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('main', 'Загрузки');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\components\grid\AdminGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'addButton' => false,
    'columns' => [
        'id',
        [
            'attribute' => 'user_id',
            'value' => 'user.username',
            //'filter' => User::getDropDown(),
        ],
        'model',
        'model_attribute',
        'object_id',
        'mime',
        [
            'label' => '',
            'format' => 'raw',
            'value' => function ($m) {
                /** @var $m app\modules\upload\models\Attachment */
                if ($m->isTypeImage()) {
                    return Html::a(
                        Html::img($m->getThumbUrl(), ['width' => 100, 'height' => 100]),
                        $m->getUrl(),
                        ['data-toggle' => 'lightbox']
                    );
                } elseif ($m->isTypeDocument()) {
                    return Html::a(
                        Html::img('/images/icons/text-file-icon.png', ['width' => 100]),
                        $m->getUrl(),
                        ['target' => 'blank']
                    );
                }
                return '';
            }
        ],
        'created_at:daterange',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {delete}',
        ],
    ],
]); ?>
