<?php

/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\LoginAsset::register($this);

use yii\bootstrap4\Html; ?>

<?php $this->beginPage() ?>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <?php $this->head() ?>

    </head>
    <body class="hold-transition login-page">

    <?php $this->beginBody() ?>

    <div class="login-box">
        <div class="login-logo">
            <b><?= Yii::t('main','Админ панель') ?></b>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?= Yii::t('main','Войдите, чтобы начать сеанс') ?></p>
                <?= $content ?>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
