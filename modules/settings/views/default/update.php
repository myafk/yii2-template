<?php

/* @var $this app\components\web\View */
/* @var $model app\modules\settings\models\Settings */

$this->title = Yii::t('main', 'Обновление параметра:') . ' ' . $model->key;
$this->params['breadcrumbs'][] = ['label' => 'Системные настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->key];
$this->params['breadcrumbs'][] = Yii::t('main', 'Обновление');
?>

<?php $this->beginTemplatePanel() ?>

<?= $this->render(empty($model->view) ? '_form' : 'forms/' . $model->view, [
    'model' => $model,
]) ?>

<?php $this->endTemplatePanel() ?>