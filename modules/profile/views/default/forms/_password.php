<?php

/**
 * @var $this \yii\web\View
 */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

\app\assets\AjaxPostFormAsset::register($this);

$model = new \app\modules\profile\models\form\ChangePassword();

?>

<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <?= Yii::t('main', 'Смена пароля') ?>
        </div>

        <?php $form = ActiveForm::begin([
            'action' => '/profile/default/change-password',
            'options' => ['class' => 'ajax-post-form'],
        ]); ?>

        <div class="card-body">

            <?= $form->errorSummary($model, ['class' => 'error-summary']) ?>

            <?= $form->field($model, 'oldPassword')->passwordInput() ?>

            <?= $form->field($model, 'newPassword')->passwordInput() ?>

            <?= $form->field($model, 'retypePassword')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('main', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>