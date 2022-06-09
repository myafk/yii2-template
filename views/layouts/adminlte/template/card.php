<?php

/**
 * @var $header string
 * @var $tools string|bool
 * @var $bodyClass string
 * @var $cardClass string
 * @var $content string
 */

$tools = isset($tools) ? $tools : false;

?>

<div class="row">
    <div class="col-md-12">
        <div class="card <?= $tools === 'close' ? 'collapsed-card' : '' ?>">

            <?php if (isset($header) || isset($tools)): ?>
            <div class="card-header">

                <?php if (!empty($header)): ?>
                <div class="card-title">
                    <?= $header ?? '' ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($tools)): ?>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-<?= $tools === 'close' ? 'plus' : 'minus'?>"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

            </div>
            <?php endif; ?>

            <div class="<?= $bodyClass ?>">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>