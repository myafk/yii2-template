<?php

namespace app\modules\profile\models\form;

use app\components\helpers\PermissionHelper;
use Yii;
use yii\base\Model;
use yii\web\Cookie;

class DebugForm extends Model
{
    protected $_canDebug;
    protected $key;
    public $debug;

    public function init()
    {
        $this->key = Yii::$app->params['cookieDebugParams'] ?? null;
        $this->_canDebug = $this->key && PermissionHelper::checkPermission(PermissionHelper::ACCESS_DEBUG);
        $this->debug = $this->key && isset($_COOKIE[$this->key]);
    }

    public function canDebug()
    {
        return $this->_canDebug;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['debug'], 'integer'],
            [['debug'], 'validateCanDebug'],
        ];
    }

    public function validateCanDebug()
    {
        if ($this->_canDebug !== true) {
            $this->addError('debug', Yii::t('main', 'Валидация не прошла #e1'));
        }
    }

    public function attributeLabels()
    {
        return [
            'debug' => Yii::t('main', 'Debug'),
        ];
    }

    public function change()
    {
        if ($this->validate()) {
            if ($this->debug) {
                $cookie = new Cookie([
                    'name' => $this->key,
                    'value' => 'on',
                    'expire' => time() + 60 * 60 * 24 * 30,
                    'secure' => YII_ENV_PROD ? true : false,
                    'httpOnly' => true,
                ]);
                Yii::$app->response->cookies->add($cookie);
            } else {
                Yii::$app->response->cookies->remove($this->key);
            }
            return true;
        }

        return false;
    }
}