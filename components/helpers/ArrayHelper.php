<?php

namespace app\components\helpers;

use app\components\base\Yii;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     *  Делает из ассоциативного массива массив масcивов, в которых
     *  добавляются ключи $keyIndex к ключам и ключи $valueIndex к значениям
     *  Пример:
     *  ```php
     *  $a = ['33' => 'First', '44' => 'Second'];
     *  $result = ArrayHelper::addIndexes($a, 'id', 'title');
     *  ```
     *  Результатом будет:
     *  [
     *      ['id' => '33', 'title' => 'First'],
     *      ['id' => '44', 'title' => 'Second']
     *  ]
     *
     * Для ключа значения поддерживается подмассив
     * Пример:
     *  ```php
     *  $a = ['33' => 'First', '44' => 'Second'];
     *  $result = ArrayHelper::addIndexes($a, 'id', 'title.label');
     *  ```
     *  Результатом будет:
     *  [
     *      ['id' => '33', 'title' => ['label' => 'First']],
     *      ['id' => '44', 'title' => ['label' => 'Second']]
     *  ]
     * @param array $array
     * @param string $keyIndex
     * @param string $valueIndex
     * @return array
     */
    public static function addIndexes($array, $keyIndex, $valueIndex)
    {
        $valueKeys = explode('.', $valueIndex);
        $globalKey = $valueKeys[0];
        $localKey = isset($valueKeys[1]) ? $valueKeys[1] : null;
        $result = [];
        foreach ($array as $k => $v) {
            if (!$localKey) {
                $result[] = [$keyIndex => $k, $globalKey => $v];
            } else {
                $result[] = [$keyIndex => $k, $globalKey => [$localKey => $v]];
            }
        }
        return $result;
    }

    public static function toCsv(string $path, array $data, $header = null, bool $append = false)
    {
        $csv = fopen($path, $append ? 'a' : 'w');
        if (!$header) {
            foreach ($data as $item) {
                foreach ($item as $h => $value) {
                    $header[] = $h;
                }
                break;
            }
        }

        if (!$append) {
            fputcsv($csv, $header ?: [], ';');
        }

        foreach ($data as $item) {
            fputcsv($csv, $item, ';');
        }

        fclose($csv);
    }

    public static function grouping(array $array, array $indexes, string $keyIndex, bool $ignoreNullKeys = false)
    {
        $result = [];
        foreach ($array as $row) {
            $key = '';
            if ($ignoreNullKeys) {
                foreach ($indexes as $index) {
                    if (isset($index) && !$row[$index]) {
                        continue 2;
                    }
                }
            }

            foreach ($indexes as $index) {
                $indexVal = $row[$index . '_data'] ?: Yii::t('main', 'Не указано');
                $key .= $indexVal . ';';
            }
            if (!isset($result[$key])) {
                $result[$key] = [];
            }
            if (isset($row[$keyIndex]) || !$ignoreNullKeys) {
                $result[$key][] = $row;
            }
        }
        return array_filter($result);
    }
}
