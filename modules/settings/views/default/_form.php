<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\Settings */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'key')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord]) ?>

<?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

<?= Html::submitButton($model->isNewRecord ? Yii::t('main', 'Создать') : Yii::t('main', 'Обновить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
