<?php

/**
 * @var $this \yii\web\View
 */

use app\modules\upload\widgets\AvatarWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

\app\assets\AjaxPostFormAsset::register($this);

?>

<div class="col-md-6">
    <div class="card card-primary text-center">
        <div class="card-header">
            <?= Yii::t('main', 'Аватар') ?>
        </div>

        <?php $form = ActiveForm::begin([
            'action' => '/profile/default/avatar',
            'options' => ['class' => 'ajax-post-form'],
        ]); ?>

        <div class="card-body">

            <?= $form->field(Yii::$app->user->identity, 'avatar_id')
                ->label(false)
                ->widget(AvatarWidget::class, [
                    'options' => [
                        'name' => 'avatar_id',
                        'href_text' => ''
                    ]
                ]); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('main', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>