<?php

namespace app\components\adminlte;

use app\assets\AdminLteAlertAsset;
use yii\bootstrap4\Widget;
use yii\web\View;

/**
 * Alert widget renders a message from session flash for AdminLTE alerts. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', '<b>Alert!</b> Danger alert preview. This alert is dismissable.');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class AdminLteAlert extends Widget
{

    const TYPE_FLASH_SUCCESS = 'success';
    const TYPE_FLASH_INFO = 'info';
    const TYPE_FLASH_WARNING = 'warning';
    const TYPE_FLASH_DANGER = 'danger';

    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the array:
     *       - class of alert type (i.e. danger, success, info, warning)
     *       - icon for alert AdminLTE
     */
    public $alertTypes = [
        self::TYPE_FLASH_SUCCESS => [
            'class' => 'bg-success',
            'icon' => '<i class="icon fa fa-check"></i>',
        ],
        self::TYPE_FLASH_INFO => [
            'class' => 'bg-info',
            'icon' => '<i class="icon fa fa-info"></i>',
        ],
        self::TYPE_FLASH_WARNING => [
            'class' => 'bg-warning',
            'icon' => '<i class="icon fa fa-warning"></i>',
        ],
        self::TYPE_FLASH_DANGER => [
            'class' => 'bg-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
    ];

    /**
     * @var boolean whether to removed flash messages during AJAX requests
     */
    public $isAjaxRemoveFlash = false;

    /**
     * Initializes the widget.
     * This method will register the bootstrap asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        AdminLteAlertAsset::register($this->view);

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $class = $this->alertTypes[$type]['class'];
                foreach ($data as $message) {
                    $params = [
                        $class,
                        $message['body'],
                        $message['title'] ?? null,
                        $message['subtitle'] ?? null,
                    ];
                    $this->view->registerJs('alertToasts(' . implode(',', array_map(function ($el) {
                            return "'$el'";
                        }, array_filter($params))) . ')', View::POS_END);
                }
                $session->removeFlash($type);
            }
        }
    }
}