<?php

namespace app\modules\user\models\traits;

trait UserQueryTrait
{
    public static function getFullNameQuery($alias = 'u.')
    {
        return "
        (IF({$alias}last_name IS NOT NULL, 
            CONCAT({$alias}last_name, ' ', 
                IF({$alias}first_name IS NOT NULL, 
                    LEFT({$alias}first_name,1),''
                ), ' ', 
                IF({$alias}patronymic IS NOT NULL,
                    LEFT({$alias}patronymic,1),
                    ''
                ), ' ' 
            ), 
            {$alias}username)
        )";
    }
}