<?php

namespace App\Documentation\Helpers;


use ArrayObject;

/**
 * Class ArrayHelper
 *
 * @package App\Documentation\Helpers
 *
 * @author Dmitry Turchanin
 */
class ArrayHelper
{
    /**
     * Recursive array merge with replacing values of simple types.
     *
     * Additionally it supports values which are instances of ReplaceArrayObject. In this case it will not merge
     * arrays with the same key and just replace older one with newer one.
     *
     * @param $a
     * @param $b
     *
     * @return mixed
     */
    public static function merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);

        while (!empty($args)) {
            foreach (array_shift($args) as $k => $v) {
                if ($v instanceof ReplaceArrayObject) {
                    $res[$k] = $v;
                } elseif (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (self::isArray($v) && isset($res[$k]) && self::isArray($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }

    /**
     * Checks if type of $data is Array.
     *
     * It additionally checks if $data is an instance of ArrayObject.
     *
     * @param $data
     *
     * @return bool
     */
    public static function isArray($data): bool
    {
        return is_array($data) || $data instanceof ArrayObject;
    }
}