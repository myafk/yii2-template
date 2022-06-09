<?php

use yii\bootstrap4\Html;

/**
 * @var $this \app\components\web\View
 * @var $usersCount integer
 * @var $activeUsersCount integer
 */

$this->title = Yii::t('main', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $usersCount ?></h3>

                <p><?= Yii::t('main', 'Пользователей') ?></p>
            </div>
            <div class="icon">
                <i class="fas fa-user-friends"></i>
            </div>
            <?= Html::a('Больше <i class="fas fa-arrow-circle-right"></i>', ['/user/user/index'], [
                    'class' => 'small-box-footer'
            ]); ?>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $activeUsersCount ?></h3>

                <p><?= Yii::t('main', 'Активных') ?></p>
            </div>
            <div class="icon">
                <i class="fas fa-eye"></i>
            </div>
            <?= Html::a('Больше <i class="fas fa-arrow-circle-right"></i>', ['/user/user/index'], [
                'class' => 'small-box-footer'
            ]); ?>
        </div>
    </div>

</div>
