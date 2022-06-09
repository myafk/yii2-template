<?php

use app\components\helpers\PermissionHelper;
use app\modules\user\models\User;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="justify-content: space-between;">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" target="_blank" class="nav-link">
                <?= Yii::t('main', 'На сайт') ?>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav float-right">

        <?php if (PermissionHelper::checkPermission('/crm/default/full-form', true)): ?>
            <li class="nav-item d-none d-sm-inline-block float-right">
                <a href="/crm/default/full-form" class="nav-link">
                    <?= Html::tag('span', '', [
                        'class' => 'fas fa-plus',
                        'title' => Yii::t('main', 'Новый заказ')
                    ]) ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if (PermissionHelper::checkPermission('/settings/system/flush-cache', true)): ?>
            <li class="nav-item d-none d-sm-inline-block float-right">
                <a href="/flush-cache" class="nav-link">
                    <?= Html::tag('span', '', [
                        'class' => 'fas fa-trash-alt',
                        'title' => Yii::t('main', 'Очистить кеш')
                    ]) ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if (Yii::$app->session->get(User::SESSION_BACK_IDENTITY_ID)): ?>
            <li class="nav-item d-none d-sm-inline-block float-right">
                <a href="/user/user/logout-by-user" class="nav-link">
                    <?= Html::tag('span', '', [
                        'class' => 'fas fa-home',
                        'title' => Yii::t('main', 'Выйти из пользователя')
                    ]) ?>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item d-none d-sm-inline-block float-right">
            <a href="/logout" data-method="post" class="nav-link">
                <?= Html::tag('span', '', [
                    'class' => 'fas fa-door-open',
                    'title' => Yii::t('main', 'Выход')
                ]) ?>
            </a>
        </li>
    </ul>
</nav>
