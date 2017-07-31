<?php

namespace Merkeleon\Logger;


use Carbon\Carbon;
use File;
use Merkeleon\Logger\Rotators\Daily;
use Merkeleon\Logger\Rotators\Single;

class Logger
{
    protected $searchDir = '';
    protected $storeDir = '';

    public function __construct($config)
    {
        $rotator = array_get($config, 'rotator');
        if (class_exists($rotator))
        {
            $this->rotator = new $rotator($config);
        }
        else
        {
            switch($rotator)
            {
                case 'daily':
                    $this->rotator = new Daily($config);
                    break;
                case 'single':
                    $this->rotator = new Single($config);
                    break;
            }
        }
    }

    public function add($filename, $event, $data)
    {
        $this->rotator->add($filename, $event, $data);
    }

    public function search($filename, $event)
    {
        return $this->rotator->search($filename, $event);
    }
}