<?php
namespace Tvart\Facebook;

class Formater
{
    public static function idx(array $array, $key, $default = null) {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

    public static function he($str) {
        return htmlentities($str, ENT_QUOTES, "UTF-8");
    }
}
