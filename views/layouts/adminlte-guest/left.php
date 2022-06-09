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
                <?= Html::img('/resource/img/default-avatar.jpg', [
                    'class' => 'img-circle elevation-2',
                ]) ?>
            </div>
            <div class="info">
                <?= Html::a('Guest', ['/profile/default/index'], ['class' => 'd-block']) ?>
            </div>
        </div>

        <nav class="mt-2">
        </nav>

    </div>

</aside>
