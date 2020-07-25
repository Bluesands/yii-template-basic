<?php

namespace app\helpers;

use Yii;
use yii\db\Exception;
use yii\helpers\BaseFileHelper;

class FileHelper extends BaseFileHelper
{
    /**
     * 文件路由地址获取
     * @param string $url
     * @param string $pathPrefix
     * @return string
     */
    public static function getFullUrl(string $url, string $pathPrefix = 'webUrl'): string
    {
        if (empty($url)) {
            return '';
        }

        if (StringHelper::startsWith($url, 'http')) {
            return $url;
        }

        $prefixDomain = rtrim(Yii::$app->params[$pathPrefix], '/');
        $url          = ltrim($url, '/');

        return $prefixDomain . '/' . $url;
    }

    /**
     * 检查文件是否存在不存在这报错
     * @param string $path
     * @return bool
     * @throws Exception
     */
    public static function isFile(string $path)
    {
        if (!is_file($path)) {
            throw new Exception($path . ' 文件未找到');
        }

        return true;
    }
}