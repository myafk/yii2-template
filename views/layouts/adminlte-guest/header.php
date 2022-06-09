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

    </ul>
</nav>
