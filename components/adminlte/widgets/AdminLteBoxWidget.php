<?php

namespace app\components\adminlte\widgets;

use yii\base\Widget;

class AdminLteBoxWidget extends Widget
{
    public string $title;
    public string $content;

    public function run()
    {
        return $this->render('box', [
            'title' => $this->title,
            'content' => $this->content,
        ]);
    }
}