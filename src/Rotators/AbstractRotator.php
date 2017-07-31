<?php

namespace Merkeleon\Logger\Rotators;


use Carbon\Carbon;
use File;

abstract class AbstractRotator
{
    protected $config;
    protected $logsDir;

    public function __construct($config)
    {
        $this->config = $config;
        $this->logsDir = storage_path(array_get($config, 'logs_dir'));
        if (!File::isDirectory($this->logsDir))
        {
            File::makeDirectory($this->logsDir, 0777);
        }
    }

    protected function lineFormat($event, $data)
    {
        return Carbon::now()->toDateTimeString().'|'.$event.'|'.json_encode($data).PHP_EOL;
    }
    public abstract function add($filename, $event, $data);
    public abstract function search($filename, $event);
}