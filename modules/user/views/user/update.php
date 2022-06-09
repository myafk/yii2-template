<?php

/* @var $this app\components\web\View */

use app\components\helpers\PermissionHelper;
use app\modules\user\models\form\UserUpdateForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $model app\modules\user\models\User */

$this->title = Yii::t('main', 'Обновление пользователя: {name}', [
    'name' => $model->username,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('main', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username];
$this->params['breadcrumbs'][] = Yii::t('main', 'Обновление');

?>

<?php $this->beginTemplatePanel() ?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-6"><?= $form->field($model, 'username')->textInput(['disabled' => true]) ?></div>
        <div class="col-6"><?= $form->field($model, 'email')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'status')->dropDownList(UserUpdateForm::getStatuses()) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'role')->dropDownList(PermissionHelper::listUserRolesByUser()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'last_name')->textInput() ?></div>
        <div class="col-4"><?= $form->field($model, 'first_name')->textInput() ?></div>
        <div class="col-4"><?= $form->field($model, 'patronymic')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'function')->textInput() ?></div>
        <div class="col-4"><?= $form->field($model, 'phone')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-12">
            <?= Yii::t('main', 'Оставьте пустыми, если не требуется смена пароля') ?>
        </div>
        <div class="col-6"><?= $form->field($model, 'newPassword')->passwordInput() ?></div>
        <div class="col-6"><?= $form->field($model, 'retypePassword')->passwordInput() ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->endTemplatePanel() ?>
