<?php

/** @var string|null $addButton */
/** @var string|null $additionalButton */
/** @var string|null $title */

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <?php if ($title): ?>
                    <?= $title ?>
                <?php endif; ?>
                <?php if (!empty($addButton)): ?>
                    <?= $addButton ?>
                <?php endif; ?>
                <?php if (!empty($additionalButton)): ?>
                    <?= $additionalButton ?>
                <?php endif; ?>

                <div class="card-tools">
                    {summary}
                </div>
            </div>


            <div class='card-body p-0'>
                {items}
            </div>

            <div class="card-footer clearfix">
                <div class="card-tools">
                    {pager}
                </div>
            </div>

        </div>
    </div>
</div>
