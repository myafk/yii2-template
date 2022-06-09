<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this app\components\web\View */
/* @var $model app\modules\upload\models\Attachment */

$this->title = Yii::t('main', 'Файл') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('main', 'Загрузки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $this->beginTemplatePanel() ?>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'user_id',
                'value' => $model->user ? $model->user->username : $model->user_id
            ],
            'model',
            'model_attribute',
            'object_id',
            'title',
            'mime',
            'path',
            'url',
            'thumbUrl',
            'titleUrl',
            'created_at',
        ],
    ]) ?>

    <?php if ($model->isTypeImage()) {
        echo Html::a(
            Html::img($model->getThumbUrl(), ['width' => 100, 'height' => 100]),
            $model->getUrl(),
            ['data-toggle' => 'lightbox']
        );
    } elseif ($model->isTypeDocument()) {
        echo Html::a(
            Html::img('/images/icons/text-file-icon.png', ['width' => 100]),
            $model->getUrl(),
            ['target' => 'blank']
        );
    } ?>

<?php $this->endTemplatePanel() ?>
