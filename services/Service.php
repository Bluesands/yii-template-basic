<?php

namespace app\services;

class Service
{
    public $err = '';

    /**
     * @var ModelFactory 模型工厂实例
     */
    protected $factory;

    public function __construct()
    {
        $this->factory = ModelFactory::builder();
    }

    /**
     * 子类实例化构建
     * @return static
     */
    public static function builder()
    {
        return new static();
    }

    /**
     * 处理列表记录
     * @param array $list
     * @return array
     */
    protected function processList(array $list)
    {
        return array_map(function ($e) {
            return $this->processRow($e);
        }, $list);
    }

    /**
     * 处理每一条记录
     * @param array $row
     * @return array
     */
    protected function processRow(array $row)
    {
        return $row;
    }
}