<?php
namespace app\helpers;

use yii\helpers\BaseInflector;
use yii\helpers\BaseStringHelper;

class StringHelper extends BaseStringHelper
{
    /**
     * 字符串转换为驼峰
     * @param string $str
     * @return string
     */
    public static function camelize(string $str)
    {
        return lcfirst(BaseInflector::camelize($str));
    }
}
