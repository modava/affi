<?php


namespace modava\affiliate\helpers;

/*
 * Implement by Hoang Duc
 * Date:    2020-07-29
 * Purpose: Provide a Util class*/

class Utils
{
    public static function decamelize($string) {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
    }
}