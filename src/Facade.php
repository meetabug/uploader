<?php


namespace Meetabug\Uploader;


class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * 获取组件的注册名称。
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Uploader::class;
    }
}