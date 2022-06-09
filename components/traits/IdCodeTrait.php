<?php

namespace app\components\traits;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @property integer $id
 * @property string $code
 */
trait IdCodeTrait
{
    protected static $_bycode = [];
    protected static $_byid = [];

    /**
     * @return string
     */
    protected static function getIdCodeCodeKey()
    {
        return defined(get_called_class() . '::ID_CODE__CODE_KEY') ? static::ID_CODE__CODE_KEY : 'code';
    }

    protected static function loadCache()
    {
        /** @var ActiveQuery $query */
        $query = static::find();
        $data = $query->all();
        static::$_byid = ArrayHelper::index($data, 'id');
        static::$_bycode = ArrayHelper::index($data, static::getIdCodeCodeKey());
    }

    /**
     * @param integer $id
     * @return string|null
     */
    public static function getCodeById($id)
    {
        if (empty(static::$_byid)) {
            static::loadCache();
        }
        return isset(static::$_byid[$id]) ? static::$_byid[$id]->{static::getIdCodeCodeKey()} : null;
    }

    /**
     * @param integer $id
     * @return static|null
     */
    public static function getById($id)
    {
        if (empty(static::$_byid)) {
            static::loadCache();
        }
        return static::$_byid[$id] ?? null;
    }

    /**
     * @param string $code
     * @return int|null
     */
    public static function getIdByCode($code)
    {
        if (empty(static::$_bycode)) {
            static::loadCache();
        }
        return isset(static::$_bycode[$code]) ? (int)static::$_bycode[$code]->id : null;
    }

    /**
     * @param array $codes
     * @return array
     */
    public static function getIdsByCodes($codes)
    {
        $codes = (array)$codes;
        if (empty(static::$_bycode)) {
            static::loadCache();
        }
        $result = [];
        foreach ($codes as $code) {
            $id = static::getIdByCode($code);
            if ($id) {
                $result[] = $id;
            }
        }
        return $result;
    }

    /**
     * @param string $code
     * @return static|null
     */
    public static function getByCode($code)
    {
        if (empty(static::$_bycode)) {
            static::loadCache();
        }
        return static::$_bycode[$code] ?? null;
    }
}