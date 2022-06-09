<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use app\components\gii\generators\crud\Generator;

/* @var $this yii\web\View */
/* @var $generator Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use <?= $generator->indexWidgetType === 'grid' ? "app\\components\\grid\\AdminGridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this app\components\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('main', <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>);
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : '' ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>AdminGridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            //'" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if ($format === Generator::COLUMN_FORMAT_STATUS_FILTER) {
            echo str_repeat(' ', 12) . "[\n";
            echo str_repeat(' ', 16) . "'attribute' => 'status',\n";
            echo str_repeat(' ', 16) . "'value' => 'statusName',\n";
            echo str_repeat(' ', 16) . "'filter' => \$searchModel::getStatuses(),\n";
            echo str_repeat(' ', 12) . "],\n";
            continue;
        }
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>

            [
                'class' => 'app\components\grid\ActionColumn',
                'template' => '{update} {delete}',
            ]
        ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
