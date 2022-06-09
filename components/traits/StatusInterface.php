<?php

namespace app\components\traits;

interface StatusInterface
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 1;

    public static function getStatuses();
    public function getStatusName();
    public function setDeleted();
}