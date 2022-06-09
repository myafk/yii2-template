<?php

/* @var $this app\components\web\View */

/* @var $model app\modules\user\models\User */

use app\components\helpers\PermissionHelper;
use app\modules\user\models\form\UserCreateForm;
use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('main', 'Создание пользователя');
$this->params['breadcrumbs'][] = ['label' => Yii::t('main', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $this->beginTemplatePanel() ?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-6"><?= $form->field($model, 'username')->textInput() ?></div>
        <div class="col-6"><?= $form->field($model, 'email')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'newPassword')->passwordInput() ?></div>
        <div class="col-4">
            <?= $form->field($model, 'status')->dropDownList(UserCreateForm::getStatuses()) ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'role')->dropDownList(PermissionHelper::listUserRolesByUser(), [
                'prompt' => ''
            ]) ?>
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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->endTemplatePanel() ?>
