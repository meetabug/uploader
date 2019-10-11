<?php


namespace Meetabug\Uploader\Services;


use Meetabug\Uploader\Exceptions\HttpException;
use Meetabug\Uploader\Exceptions\InvalidParamException;
use OSS\OssClient;

class OssServer implements ServerInterface
{
    protected $config;
    
    /**
     * 设置配置
     * @param  array  $config
     * @return ServerInterface
     * @throw InvalidParamException
     * @throws InvalidParamException
     */
    public function config(array $config): ServerInterface
    {
        
        if (empty($config['accessKeyId']) || empty($config['accessKeySecret']) || empty($config['bucket']) || empty($config['endpoint'])) {
            throw new InvalidParamException('server param invalid');
        }
        $this->config = $config;
        return $this;
    }
    
    /**
     * OSS服务
     * @return OssClient
     * @throws \OSS\Core\OssException
     */
    public function getHttpClient()
    {
        return new OssClient($this->config['accessKeyId'], $this->config['accessKeySecret'], $this->config['endpoint']);
        
    }
    
    public function upload(string $file): string
    {
        if (!is_file($file)) {
            throw new InvalidParamException($file.' is not a file');
        }
        try {
            $res = $this->getHttpClient()->uploadFile($this->config["bucket"], $this->getFileName($file), $file);
            return $res['oss-request-url'];
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * 随机文件名
     * @param  string  $file
     * @return string
     */
    public function getFileName(string $file): string
    {
        $extension = substr($file, strrpos($file, '.'));
        return md5($file).time().$extension;
    }
}