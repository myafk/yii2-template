<?php

namespace app\components\traits;

use Yii;

/**
 * Trait DeleteTrait
 * @package app\components\traits
 *
 * @property int $status
 */
trait StatusTrait
{
    public static function getStatuses()
    {
        return [
            StatusInterface::STATUS_ACTIVE => Yii::t('main', 'Активный'),
            StatusInterface::STATUS_INACTIVE => Yii::t('main', 'Неактивный'),
            StatusInterface::STATUS_DELETED => Yii::t('main', 'Удаленный'),
        ];
    }

    public function getStatusName()
    {
        return self::getStatuses()[$this->status] ?? Yii::t('main', 'Не указан');
    }

    public function setDeleted()
    {
        $this->status = StatusInterface::STATUS_DELETED;
        $this->save(false);
    }
}