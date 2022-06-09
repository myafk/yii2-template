<?php

namespace app\components\helpers;

class Select2Helper
{

    const FORMAT_SETTING_KEYS = '$key';
    const FORMAT_USERS = '$fullName';

    public static function getVariablesFromFormat($format):array
    {
        preg_match_all('/\$([a-zA-Z_]+)/', $format, $matches);
        return $matches[1] ?? [];
    }

    public static function format(string $format, string|array|object|null $data):string|null
    {
        if (!$data) {
            return null;
        }
        $vars = self::getVariablesFromFormat($format);
        $replace = [];
        foreach ($vars as $var) {
            $dataVar = is_array($data) || is_object($data) ? ($data[$var] ?? null) : $data;
            $replace['$' . $var] = $dataVar;
        }

        return strtr($format, $replace);
    }

    public static function formatArray(string $format, array $array, ?string $key = 'id', ?string $keyValue = 'id'):array
    {
        $formatted = [];
        foreach ($array as $item) {
            $formatted[] = [$key => $item[$keyValue], 'text' => self::format($format, $item)];
        }

        return $formatted;
    }

}
