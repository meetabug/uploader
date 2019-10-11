<?php


namespace Meetabug\Uploader\Services;


interface ServerInterface
{
    public function config (array $config): ServerInterface;
    
    public function upload(string  $file): string;
}