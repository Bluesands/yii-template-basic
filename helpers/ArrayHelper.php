<?php
namespace app\helpers;

use yii\helpers\BaseInflector;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * 驼峰转换数组的键
     * @param array $arr
     * @return array
     */
    public static function camelizeKeys($arr)
    {
        $ignoreKeyStart = [
            '/',
            '-',
        ];
        $newArr         = [];
        foreach ($arr as $k => $v) {
            $isIgnore = in_array(substr(strval($k), 0, 1), $ignoreKeyStart);
            $k        = $isIgnore ? $k : StringHelper::camelize($k);

            if (is_array($v) || is_object($v)) {
                $newArr[$k] = self::camelizeKeys((array)$v);
            } else {
                $newArr[$k] = $v;
            }
        }

        return $newArr;
    }

    /**
     * 下划转换数组的键
     * @param array $arr
     * @return array
     */
    public static function underscoreKeys($arr)
    {
        $newArr = [];
        foreach ($arr as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $newArr[BaseInflector::underscore($k)] = self::underscoreKeys((array)$v);
            } else {
                $newArr[BaseInflector::underscore($k)] = $v;
            }
        }

        return $newArr;
    }

    /**
     * 得到数组树
     * @param array  $arr
     * @param int    $pStart
     * @param string $pKey
     * @param string $cKey
     * @return array
     */
    public static function getTree(array $arr, int $pStart = 0, string $pKey = 'id', string $cKey = 'parent_id'): array
    {
        $tree = [];

        foreach ($arr as $item) {
            if ($item[$cKey] == $pStart) {
                $children = self::getTree($arr, $item[$pKey], $pKey, $cKey);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }

    /**
     * 分组
     * @param array  $arr
     * @param string $key
     * @return array
     */
    public static function groupBy(array $arr, string $key): array
    {
        $result = [];
        foreach ($arr as $e) {
            $result[$e[$key]][] = $e;
        }

        return $result;
    }

    public static function jsonDeepToArray($str): array
    {
        if (is_string($str)) {
            $str = json_decode($str);
        }
        $arr = [];
        foreach ($str as $k => $v) {
            if (is_object($v) || is_array($v)) {
                $arr[$k] = self::jsonDeepToArray($v);
            } else {
                $arr[$k] = $v;
            }
        }

        return $arr;
    }

    /**
     * 二维数组排序
     * @param array $array
     * @param       $on
     * @param int   $order
     * @return array
     */
    public static function twoDimArraySort(array $array, $on, $order = SORT_ASC)
    {
        $result        = [];
        $sortableArray = [];

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    $sortableArray[$k] = $v[$on];
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortableArray);
                    break;
                case SORT_DESC:
                    arsort($sortableArray);
                    break;
            }

            foreach ($sortableArray as $k => $v) {
                $result[] = $array[$k];
            }
        }

        return $result;
    }
}
