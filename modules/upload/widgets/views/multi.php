<?php

use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $widget app\modules\upload\widgets\MultiuploadWidget */
/** @var $options array */

?>

<?php /**
<script>
    function set_hidden_input(id) {
        var input = document.getElementById('<?=$options['id'];?>');
        var value = input.value;
        if (value) {
            value = value.split(',');
        } else {
            value = [];
        }
        value.push(id);
        input.value = value.join(',');
    }
</script>
 * */  // TODO ?>

<div class="row">
    <div class="col-10">
        <?= Html::tag('div', '<div class="progress-bar progress-bar-success"></div>',
            $options['progress_bar']
        ); ?>
    </div>
    <div class="col-2 text-right">
        <?= $widget->loadFileInput(); ?>
        <?= Html::hiddenInput($options['name'], "", ['id' => $options['id']]); ?>
    </div>
</div>

<?= Html::tag('div', '', [
    'id' => $options['img_id'],
    'class' => $options['div_class'],
]); ?>

<div class="clearfix"></div>