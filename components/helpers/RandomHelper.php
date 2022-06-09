<?php

namespace app\components\helpers;

use Yii;

class RandomHelper
{
    /**
     * Генерирует рандомную строку цифр, букв анг.алф и символов _-
     * @param int $length
     * @return string
     */
    public static function getRnd($length = 6)
    {
        return Yii::$app->security->generateRandomString($length);
    }

    /**
     * Генерирует рандомную строку букв анг.алф
     * @param int $length
     * @return string
     */
    public static function getRandomString($length = 6)
    {
        return strtolower(strtr(self::getRnd($length), '_-0123456789', 'abcdefghjqrs'));
    }

    /**
     * Генерирует рандомную строку цифр, букв анг.алф
     * @param int $length
     * @return string
     */
    public static function getRandomKey($length = 6)
    {
        return strtr(self::getRnd($length), '_-', '01');
    }

    /**
     * Создает словарь с рандомными осмысленными словами анг.алф
     * @param string $path Путь до файла
     * @param int $count Число слов
     * @return bool
     */
    public static function setDictionaryFile($path, $count = 300)
    {
        try {
            $data = [];
            while ($count--) {
                $data[] = ucfirst(self::getRandomPronounceableWord(rand(5, 9)));
            }
            file_put_contents($path, implode("\r\n", $data));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Генерирует рандомное осмысленное слово
     * @param int $length
     * @return string
     */
    public static function getRandomPronounceableWord($length = 6)
    {
        // consonant sounds
        $cons = array(
            // single consonants. Beware of Q, it's often awkward in words
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'z',
            // possible combinations excluding those which cannot start a word
            'pt', 'gl', 'gr', 'ch', 'ph', 'ps', 'sh', 'st', 'th', 'wh',
        );

        // consonant combinations that cannot start a word
        $cons_cant_start = array(
            'ck', 'cm',
            'dr', 'ds',
            'ft',
            'gh', 'gn',
            'kr', 'ks',
            'ls', 'lt', 'lr',
            'mp', 'mt', 'ms',
            'ng', 'ns',
            'rd', 'rg', 'rs', 'rt',
            'ss',
            'ts', 'tch',
        );

        // wovels
        $vows = array(
            // single vowels
            'a', 'e', 'i', 'o', 'u', 'y',
            // vowel combinations your language allows
            'ee', 'oa', 'oo',
        );

        // start by vowel or consonant ?
        $current = (mt_rand(0, 1) == '0' ? 'cons' : 'vows');

        $word = '';

        while (strlen($word) < $length) {

            // After first letter, use all consonant combos
            if (strlen($word) == 2) {
                $cons = array_merge($cons, $cons_cant_start);
            }

            // random sign from either $cons or $vows
            $rnd = ${$current}[mt_rand(0, count(${$current}) - 1)];

            // check if random sign fits in word length
            if (strlen($word . $rnd) <= $length) {
                $word .= $rnd;
                // alternate sounds
                $current = ($current == 'cons' ? 'vows' : 'cons');
            }
        }

        return $word;
    }

    /** COLORS */

    private static $_colors = [];
    public static function getRandomRGBA()
    {
        $r = rand() % 230;
        $g = rand() % 230;
        $b = rand() % 230;
        $color = "rgba($r,$g,$b,1)";
        if (in_array($color, self::$_colors)) {
            return self::getRandomRGBA();
        }
        self::$_colors[] = $color;
        return $color;
    }

}