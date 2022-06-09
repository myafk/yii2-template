<?php

namespace app\components\gii\generators\model;

class Generator extends \yii\gii\generators\model\Generator
{
    public function generateRules($table)
    {
        $rules = parent::generateRules($table);
        return str_replace('::className()', '::class', $rules);
    }
}