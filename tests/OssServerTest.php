<?php

use PHPUnit\Framework\TestCase;
use Meetabug\Uploader\Services\OssServer;
use Mockery\Matcher\AnyArgs;
use Meetabug\Uploader\Exceptions\InvalidParamException;

class OssServerTest extends TestCase
{
    public function testConfig()
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('server param invalid');
        $oss = new OssServer();
        $oss->config([
            'accessKeyIda'    => 'test',
            'accessKeySecret' => 'test',
            'bucket'          => 'test',
            'endpoint'        => 'test',
        ]);
        $this->fail('config param exception fail');
    }
    
    public function testGetFileName()
    {
        $oss = Mockery::mock(OssServer::class)->makePartial();
        $this->assertStringEndsWith('.jpeg', $oss->getFileName('a.jpeg'));
    }
    
    public function testUploadParamFile()
    {
        $oss = \Mockery::mock(OssServer::class)->makePartial();
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('a.jpeg is not a file');
        $oss->upload('a.jpeg');
        $this->fail('ossClient request param invalid');
    }
    
    public function testUpload()
    {
        $client = \Mockery::mock(\OSS\OssClient::class);
        $client->allows()->uploadFile(new AnyArgs())->andReturn([
            'oss-request-url' => __FILE__,
        ]);
        $oss = \Mockery::mock(OssServer::class)->makePartial();
        $oss->allows()->getHttpClient()->andReturn($client);
        return $this->assertSame(__FILE__, $oss->upload(__FILE__));
    }
}