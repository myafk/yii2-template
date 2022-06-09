<?php

/**
 * @var $this \yii\web\View
 */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$model = new \app\modules\profile\models\form\DebugForm();

?>

<?php if ($model->canDebug()): ?>

<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <?= Yii::t('main', 'Системная отладка') ?>
        </div>

        <?php $form = ActiveForm::begin([
            'action' => '/profile/default/debug',
        ]); ?>

        <div class="card-body">

            <?= $form->field($model, 'debug')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('main', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php endif; ?>