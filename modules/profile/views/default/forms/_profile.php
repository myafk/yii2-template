<?php

/**
 * @var $this \yii\web\View
 */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

\app\assets\AjaxPostFormAsset::register($this);

$model = new \app\modules\profile\models\form\ProfileForm();

?>

<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <?= Yii::t('main', 'Основное') ?>
        </div>

        <?php $form = ActiveForm::begin([
            'action' => '/profile/default/profile',
            'options' => ['class' => 'ajax-post-form'],
        ]); ?>


        <div class="card-body">

            <?= $form->errorSummary($model, ['class' => 'error-summary']) ?>

            <?= $form->field($model, 'last_name')->textInput() ?>

            <?= $form->field($model, 'first_name')->textInput() ?>

            <?= $form->field($model, 'patronymic')->textInput() ?>

            <?= $form->field($model, 'function')->textInput() ?>

            <?= $form->field($model, 'phone')->textInput(['type' => 'tel']) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('main', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>