<?php

namespace app\components\grid;

use app\components\helpers\PermissionHelper;
use Yii;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\Url;

class AdminGridView extends GridView
{

    public string|null $addButton = null;
    public string|null $additionalButton = null;
    public string|null $title = null;

    public $dataColumnClass = 'app\components\grid\DataColumn';

    public $pager = [
        'class' => AdminLinkPager::class
    ];

    public function __construct(array $config = [])
    {
        $url = Url::toRoute('create');
        if (PermissionHelper::checkPermission($url, true)) {
            $this->addButton = Html::a(Yii::t('main', 'Добавить'), [$url], ['class' => 'btn btn-success']);
        }

        parent::__construct($config);

        $this->layout = Yii::$app->view->render('@app/components/grid/layouts/admin', [
            'title' => $this->title,
            'addButton' => $this->addButton,
            'additionalButton' => $this->additionalButton,
        ]);
    }

}
