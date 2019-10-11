<?php


namespace Meetabug\Uploader;


use Meetabug\Uploader\Exceptions\HttpException;
use Meetabug\Uploader\Exceptions\InvalidParamException;
use Meetabug\Uploader\Exceptions\ServerDisposeException;
use Meetabug\Uploader\Services\OssServer;

class Uploader
{
    protected $config;
    
    /**
     * 服务列表
     * @var array
     */
    protected $servers = [
        'oss' => OssServer::class,
    ];
    
    public function config(array $config): Uploader
    {
        $this->config = $config;
        return $this;
    }
    
    public function upload(string $file, string $service = 'oss'): string
    {
        if (is_file($file)) {
            throw new InvalidParamException('invalid file param');
        }
        if (!in_array($service, ['oss', 'local'])) {
            throw new ServerDisposeException('service dones not exists'.$service);
        }
        try {
            $serverInstance = new $this->servers[$service];
            return $serverInstance->config($this->config[$service])->upload($file);
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}