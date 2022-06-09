<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\components\gii\generators\crud\Generator */

echo "<?php\n";
?>

/* @var $this app\components\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = Yii::t('main', <?= $generator->generateString('Создание ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>);
$this->params['breadcrumbs'][] = ['label' => Yii::t('main', <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo "<?php "; ?> $this->beginTemplatePanel() ?>

<?= "<?= " ?>$this->render('_form', [
    'model' => $model,
]) ?>

<?php echo "<?php "; ?> $this->endTemplatePanel() ?>
