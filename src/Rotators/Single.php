<?php

namespace Merkeleon\Logger\Rotators;


use Carbon\Carbon;
use File;
use GlobIterator;
use Symfony\Component\Finder\SplFileInfo;

class Single extends AbstractRotator
{
    protected $workingDir;

    public function __construct($config)
    {
        parent::__construct($config);

        $this->workingDir = $this->logsDir . DIRECTORY_SEPARATOR . Carbon::now()
                                                                         ->toDateString();
        if (!File::isDirectory($this->workingDir))
        {
            File::makeDirectory($this->workingDir, 0777);
        }
    }

    public function add($filename, $event, $data)
    {
        $path = $this->workingDir . DIRECTORY_SEPARATOR . $filename;
        File::append($path, $this->lineFormat($event, $data));
    }

    public function search($filename, $event)
    {
        $searchStr = '|'.$event.'|';
        $result = collect();
        $dir = new GlobIterator($this->logsDir.DIRECTORY_SEPARATOR.$filename);
        foreach ($dir as $file)
        {
            /** @var $file SplFileInfo */
            $handle = fopen($file->getRealPath(), 'r');
            while (($buffer = fgets($handle)) !== false)
            {
                $buffer = trim($buffer);
                if (strpos($buffer, $searchStr) !== false)
                {
                    $result->push(json_decode(array_last(explode('|',$buffer)), true));
                }
            }
        }
        return $result;
    }
}