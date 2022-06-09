<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;

/**
 * @var $self app\modules\upload\widgets\AvatarWidget
 * @var $attachment app\modules\upload\models\Attachment
 */

?>

<?= Html::hiddenInput($self->options['name'], $self->model->{$self->attribute}, [
    'id' => $self->options['id'],
]); ?>

<a onclick="$('#<?= $self->attribute ?>_modal').modal('show');" class="upload_text">
    <div class='upload_hover'>
        <div class='text'><?= $self->options['href_text'] ?></div>
    </div>
    <?php echo Html::img($attachment->getTitleUrl(), [
        'id' => $self->options['img_id'],
        'width' => $self->options['width_img'],
        'class' => 'avatar img-circle img-fluid',
        'data-toggle' => 'tooltip',
        'data-position' => '{"my":"center bottom","at":"center bottom"}',
        'title' => Yii::t('main', 'Нажмите для изменения')
    ]); ?>
</a>


<div class="modal fade" id="<?= $self->attribute; ?>_modal" tabindex="-1" role="dialog"
     aria-labelledby="<?= $self->attribute; ?>_modal_label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <?= Html::hiddenInput('action', $self->options['action']); ?>
            <?= Html::hiddenInput('model_class',
                $self->options['model_class'] ?? StringHelper::basename(get_class($self->model))); ?>
            <?= Html::hiddenInput('model_id', $self->model->id); ?>
            <?= Html::hiddenInput('model_attribute', $self->attribute); ?>

            <div class="modal-header">
                <h4 class="modal-title text-center" id="<?= $self->attribute; ?>_modal_label">
                    <?= Yii::t('main', 'Загрузить аватар') ?>
                </h4>
            </div>

            <div class="modal-body">

                <div class="awesome-avatar-widget">

                    <div style="display:none">
                        <input id="<?= $self->attribute; ?>-x1" type="hidden" name="image-x1">
                        <input id="<?= $self->attribute; ?>-y1" type="hidden" name="image-y1">
                        <input id="<?= $self->attribute; ?>-x2" type="hidden" name="image-x2">
                        <input id="<?= $self->attribute; ?>-y2" type="hidden" name="image-y2">
                        <input id="<?= $self->attribute; ?>-ratio" type="hidden" name="image-ratio">
                        <input type="hidden" name="image-title" value="1">
                    </div>

                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <div id="<?= $self->attribute; ?>-preview" class="awesome-avatar-preview"
                                     style="width:100px;height:100px">
                                    <?= Html::img($attachment->getTitleUrl(), [
                                        'width' => '100',
                                        'height' => '100',
                                    ]) ?>
                                </div>
                            </td>
                            <td>
                                <div id="<?= $self->attribute; ?>-select-area" class="awesome-avatar-select-area"
                                     style="width:400px;height:250px">
                                    <img src="<?= $attachment->getTitleUrl(); ?>">
                                </div>
                                <input type="file" class="awesome-avatar-input" id="<?= $self->attribute; ?>"
                                       name="files" accept="image/jpeg,image/png,image/gif">
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="modal-footer">
                <button id="modal-button-send" type="button"
                        class="btn btn-primary"><?= Yii::t('main', 'Загрузить') ?></button>
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= Yii::t('main', 'Закрыть') ?></button>
            </div>
        </div>
    </div>
</div>