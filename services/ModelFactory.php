<?php

namespace app\services;

class ModelFactory
{
    /**
     * @return ModelFactory
     */
    public static function builder()
    {
        return new ModelFactory();
    }
}