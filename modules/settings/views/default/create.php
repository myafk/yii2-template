<?php

/* @var $this app\components\web\View */
/* @var $model app\modules\settings\models\Settings */

$this->title = Yii::t('main', 'Создание параметра');
$this->params['breadcrumbs'][] = ['label' => 'Системные настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginTemplatePanel() ?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>

<?php $this->endTemplatePanel() ?>