<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?= Yii::t('main', 'Из-за ошибки веб-сервер не смог обработать ваш запрос') ?>
    </p>
    <p>
        <?= Yii::t('main', 'Свяжитесь с администраторами сервера, если увидели данную ошибку. Спасибо') ?>
    </p>

</div>
