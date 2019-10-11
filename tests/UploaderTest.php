<?php

use PHPUnit\Framework\TestCase;
use Meetabug\Uploader\Exceptions\InvalidParamException;

class UploaderTest extends TestCase
{
    public function testUploadParamException()
    {
        $uploader = new \Meetabug\Uploader\Uploader([]);
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('invalid file param');
        $uploader->upload('test.php', 'oss');
        $this->fail('server param exception');
    }
}