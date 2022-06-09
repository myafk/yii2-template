<?php

namespace app\components\traits;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use Closure;

/**
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property integer $status
 */
trait ListTrait
{
    use IdCodeTrait;

    protected static function getCacheKey(?bool $byCode = false, ?bool $active = false): string
    {
        return 'ddt_' . Inflector::underscore(StringHelper::basename(get_called_class())) .
            '_' . Yii::$app->language . '_' . (int)$byCode . '_' . (int)$active;
    }

    /**
     * @param bool $active
     * @return ActiveQuery
     */
    public static function getQueryDropDown(?bool $active = true): ActiveQuery
    {
        /** @var ActiveQuery $query */
        $query = static::find();
        $query->orderBy('title');
        if ($active) {
            $query->andWhere(['status' => StatusInterface::STATUS_ACTIVE]);
        }
        return $query;
    }

    /**
     * Возвращает данные для дропдаунов. id/code => title
     * @param bool $byCode - сделать ключами код
     * @param bool $active - выводить только активные
     * @param callable|null $callback - функция влияющая на запрос (можно добавить доп условия)
     * @return array
     */
    public static function getList(?bool $byCode = false, ?bool $active = false, ?Closure $callback = null): array
    {
        $cacheKey = static::getCacheKey($byCode, $active);
        return Yii::$app->cache->getOrSet($cacheKey, function() use ($byCode, $active, $callback) {
            $indexBy = $byCode ? static::getIdCodeCodeKey() : 'id';
            $query = static::getQueryDropDown($active);
            if ($callback) {
                $callback($query);
            }
            return ArrayHelper::map($query->all(), $indexBy, function ($m) {
                return $m->title;
            });
        }, 3600);
    }

    /**
     * Возвращает данные id => code
     * @param bool $active выводить только активные
     * @param bool $indexByCode сделать code => id
     * @return array
     */
    public static function getMap(bool $active = false, bool $indexByCode = false):array
    {
        /** @var ActiveQuery $query */
        $query = static::find();
        $query->orderBy(static::getIdCodeCodeKey());
        if ($active) {
            $query->andWhere(['status' => StatusInterface::STATUS_ACTIVE]);
        }
        if ($indexByCode) {
            return ArrayHelper::map($query->all(), static::getIdCodeCodeKey(), 'id');
        }
        return ArrayHelper::map($query->all(), 'id', static::getIdCodeCodeKey());
    }

    public static function isActive(string $code):bool
    {
        $model = static::getByCode($code);
        if (!$model) {
            return false;
        }
        return $model->status === StatusInterface::STATUS_ACTIVE;
    }
}
