<?php

use app\components\helpers\PermissionHelper as PH;
use \yii\bootstrap4\Html;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="/" class="brand-link">
        <img src="/resource/img/logo.png" alt="L" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">HR</span>
    </a>

    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?= Html::img(Yii::$app->user->identity->getAvatarUrl(), [
                    'class' => 'img-circle elevation-2',
                    'alt' => '/resource/img/default-avatar.jpg',
                ]) ?>
            </div>
            <div class="info">
                <?= Html::a(Yii::$app->user->identity->getFullName(), ['/profile/default/index'], ['class' => 'd-block']) ?>
            </div>
        </div>

        <nav class="mt-2">
            <?= \app\components\adminlte\AdminLteMenu::widget(
                [
                    'options' => [
                        'class' => 'nav nav-pills nav-sidebar flex-column',
                        'data-widget' => 'treeview',
                        'role' => 'menu',
                        'data-accordion' => 'false',
                    ],
                    'items' => [
                        ['label' => Yii::t('main', 'Dashboard'), 'icon' => 'tachometer-alt', 'url' => ['/dashboard/default/index']],
                        ['label' => Yii::t('main', 'Hr'), 'icon' => 'home',
                            'items' => \app\modules\main\components\Menu::items()
                        ],
                        ['label' => Yii::t('main', 'Настройки'), 'icon' => 'cog', 'items' => [
                            ['label' => Yii::t('main', 'Системные'), 'icon' => 'circle-notch', 'url' => ['/settings/default/index']],
                        ]],

                        ['label' => Yii::t('main', 'Пользователи'), 'icon' => 'user-friends', 'url' => ['/user/user/index']],
                        ['label' => Yii::t('main', 'RBAC'), 'icon' => 'user-tag', 'url' => ['/admin'],
                            'visible' => PH::checkPermission(PH::ACCESS_RBAC)
                        ],
                        ['label' => Yii::t('main', 'Загрузки'), 'icon' => 'upload', 'url' => ['/upload/upload/index']], // TODO
                    ],
                ]
            ) ?>
        </nav>

    </div>

</aside>
