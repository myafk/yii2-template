<?php

namespace app\components\gii\generators\crud;

use yii\db\Schema;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;

class Generator extends \yii\gii\generators\crud\Generator
{
    const COLUMN_FORMAT_STATUS_FILTER = 'statusfilter';
    const FIELD_NAME_STATUS = 'status';
    const FIELD_NAME_CODE = 'code';
    const FIELD_NAME_TITLE = 'title';

    public $baseControllerClass = 'app\components\controllers\BaseController';

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $types['date'][] = $column->name;
                    break;
                case Schema::TYPE_TIME:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            switch ($type) {
                case 'date':
                    $rules[] = "[['" . implode("', '", $columns) . "'], '$type', 'format' => DateHelper::FORMAT_DATE_RANGE]";
                    break;
                default:
                    $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
            }

        }

        return $rules;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        $dateConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_TIME:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $dateConditions[] = $column;
                    break;
                default:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', '{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }
        if (!empty($dateConditions)) {
            foreach ($dateConditions as $column) {
                $conditions[] = "if (\$this->{$column}) {" . str_repeat(' ', 12)
                    . "\$query->andWhere(DateHelper::getBetweenQuery(\$this->{$column}));"
                    . str_repeat(' ', 8) . '}';
            }
        }

        return $conditions;
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            }

            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }

        if ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        }

        if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
            $input = 'passwordInput';
        } else {
            $input = 'textInput';
        }

        if (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }
            return "\$form->field(\$model, '$attribute')->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
        }

        if ($column->name === self::FIELD_NAME_STATUS) {
            return "\$form->field(\$model, '$attribute')->dropDownList(\$model::getStatuses())";
        }

        if ($column->name === self::FIELD_NAME_CODE) {
            return "\$form->field(\$model, '$attribute')->textInput(['maxlength' => true, 'data-urlfy' => 'code'])";
        }

        if ($column->name === self::FIELD_NAME_TITLE) {
            return "\$form->field(\$model, '$attribute')->textInput(['maxlength' => true, 'data-urlfy' => 'title'])";
        }

        if ($column->phpType !== 'string' || $column->size === null) {
            return "\$form->field(\$model, '$attribute')->$input()";
        }

        return "\$form->field(\$model, '$attribute')->$input(['maxlength' => true])";
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean') {
            return 'boolean';
        }

        if ($column->type === 'text') {
            return 'ntext';
        }

        if (in_array($column->type, ['datetime', 'date', 'timestamp'])) {
            return 'daterange';
        }

        if ($column->name === self::FIELD_NAME_STATUS) {
            return self::COLUMN_FORMAT_STATUS_FILTER;
        }

        if (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        }

        if (stripos($column->name, 'email') !== false) {
            return 'email';
        }

        if (preg_match('/(\b|[_-])url(\b|[_-])/i', $column->name)) {
            return 'url';
        }

        return 'text';
    }

    public function isStatusField()
    {
        $names = $this->getColumnNames();
        return in_array(self::FIELD_NAME_STATUS, $names);
    }
}