<?php

namespace app\modules\log\models;

use Yii;
use yii\base\Controller;
use yii\helpers\Json;

/**
 * This is the model class for table "server_log".
 *
 * @property integer $id
 * @property string $command
 * @property string $action
 * @property integer $status
 * @property string $custom_data
 * @property string $request
 * @property string $response
 *
 * @property string $created_at
 */
class ServerLog extends \yii\db\ActiveRecord
{
    const LEVEL_ERROR = 0;
    const LEVEL_INFO = 1;
    const LEVEL_WARN = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%server_logs}}';
    }

    /**
     * @param Controller|string $cmd
     * @param string $action
     * @param array|string|null $data
     */
    public static function info($cmd, $action, $data = null)
    {
        self::log(self::LEVEL_INFO, $cmd, $action, $data);
    }

    /**
     * @param Controller|string $cmd
     * @param string $action
     * @param array|string|null $data
     */
    public static function warn($cmd, $action, $data = null)
    {
        self::log(self::LEVEL_WARN, $cmd, $action, $data);
    }

    /**
     * @param Controller|string $cmd
     * @param string $action
     * @param array|string|null $data
     */
    public static function error($cmd, $action, $data = null)
    {
        self::log(self::LEVEL_ERROR, $cmd, $action, $data);
    }

    /**
     * @param string $status
     * @param Controller|string $cmd
     * @param string $action
     * @param \Exception|array|string|null $data
     */
    public static function log($status, $cmd, $action, $data = null)
    {

        if ($cmd instanceof Controller) {
            $cmd = $cmd->route;
        }

        $log = new self();
        $log->command = $cmd;
        $log->action = $action;
        $log->status = $status;

        if ($data instanceof \Exception) {
            $log->custom_data = $data->getMessage();
        } elseif (is_array($data) && count($data) > 0) {
            $log->custom_data = Json::encode($data);
        } else if (!empty($data)) {
            $log->custom_data = $data;
        }

//        if ($data instanceof ServiceException) { // TODO
//            $log->request = $data->getRawreq();
//            $log->response = $data->getRawres();
//        }

        $log->save(false);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['command', 'action', 'status'], 'required'],
            [['command', 'action'], 'string', 'max' => 255],
            [['status'], 'integer'],
            [['request', 'response', 'custom_data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'command' => Yii::t('main', 'Команда'),
            'action' => Yii::t('main', 'Действие'),
            'status' => Yii::t('main', 'Статус'),
            'custom_data' => Yii::t('main', 'Данные'),
            'request' => Yii::t('main', 'Запрос'),
            'response' => Yii::t('main', 'Ответ'),
            'created_at' => Yii::t('main', 'Дата создания'),
        ];
    }
}
