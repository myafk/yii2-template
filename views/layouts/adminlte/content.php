<?php

use yii\bootstrap4\Breadcrumbs;
use app\components\adminlte\AdminLteAlert;

/**
 * @var $this \yii\web\View
 */

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $this->title ?></h1>
                </div>
                <div class="col-sm-6">
                    <?= Breadcrumbs::widget([
                        'options' => ['class' => 'breadcrumb float-sm-right'],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?= AdminLteAlert::widget() ?>
            <?= $content ?>
        </div>
    </section>
</div>

<footer class="main-footer">

</footer>