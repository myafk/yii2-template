<?php

namespace app\components\grid;

use yii\widgets\LinkPager;

class AdminLinkPager extends LinkPager
{
    public $options = ['class' => 'pagination pagination-sm'];
    public $pageCssClass = 'page-item';
    public $linkOptions = ['class' => 'page-link'];
    public $disabledPageCssClass = 'disabled page-link';
}