<?php

/**
 * @var $this \yii\web\View
 */

$this->title = Yii::t('main', 'Профиль');

$this->params['breadcrumbs'][] = ['label' => Yii::t('main', 'Профиль')];

?>

<div class="row">

    <?= $this->render('forms/_avatar') ?>

    <?= $this->render('forms/_profile') ?>

    <?= $this->render('forms/_password') ?>

    <?= $this->render('forms/_debug') ?>

</div>


