<?php

/**
 * @var $params array
 * @var $content string
 * @var $this app\components\web\View
 */

?>

<div class="modal-dialog">
    <div class="modal-content bg-secondary">
        <div class="modal-header">
            <h4 class="modal-title"><?= $params['title'] ?? '' ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <?= $content ?>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-outline-light" data-dismiss="modal">
                <?= $params['button-close'] ?? Yii::t('main', 'Закрыть') ?>
            </button>
            <button type="submit" id="modal-send-button" class="btn btn-outline-light">
                <?= $params['button-text'] ?? Yii::t('main', 'Сохранить') ?>
            </button>
        </div>
    </div>
</div>