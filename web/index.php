<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

require_once 'local.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../config/web.php',
    require __DIR__ . '/../config/web-local.php'
);

(new yii\web\Application($config))->run();
