<?php

namespace Merkeleon\Logger\Rotators;


use Carbon\Carbon;
use File;
use GlobIterator;
use Symfony\Component\Finder\SplFileInfo;

class Daily extends AbstractRotator
{
    protected $workingDir;

    public function __construct($config)
    {
        parent::__construct($config);

        $this->workingDir = $this->logsDir . DIRECTORY_SEPARATOR . Carbon::now()
                                                                         ->toDateString();
        if (!File::isDirectory($this->workingDir))
        {
            File::makeDirectory($this->workingDir, 0777, true);
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
        $dir = new GlobIterator($this->logsDir.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.$filename);
        foreach ($dir as $file)
        {
            /** @var $file SplFileInfo */
            $handle = fopen($file->getRealPath(), 'r');
            while (($buffer = fgets($handle)) !== false)
            {
                $buffer = trim($buffer);
                if (strpos($buffer, $searchStr) !== false)
                {
                    $parts = explode('|',$buffer);
                    $row = json_decode(array_last($parts), true);
                    $row['created_at'] = array_first($parts);
                    $result->push($row);
                }
            }
        }
        return $result;
    }
}
