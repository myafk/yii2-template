<?php

namespace app\components\controllers;

use yii\console\Controller;
use yii\helpers\Console;

class BaseConsoleController extends Controller
{
    public function stdout($string)
    {
        $string = date("[y-m-d H:i:s] - ") . $string . PHP_EOL;
        if ($this->isColorEnabled()) {
            $args = func_get_args();
            array_shift($args);
            $string = Console::ansiFormat($string, $args);
        }
        return Console::stdout($string);
    }
}
