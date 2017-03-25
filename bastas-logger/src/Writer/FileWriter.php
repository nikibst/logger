<?php
/**
 * Created by PhpStorm.
 * User: nikitas
 * Date: 25/2/2017
 * Time: 09:42
 */

namespace Bastas\Logger\Writer;

use Bastas\Logger\Exception\FileWriterException;
use Bastas\Logger\LogSet;

final class FileWriter extends Writer implements WriterInterface
{
    const FILE_SUFFIX = '.log';

    private $filePathName = '';
    private $perDay = false;

    public function __construct(array $config)
    {
        if(!isset($config['FileName'])) {
            throw new FileWriterException("You must specify a valid file name");
        }

        if(!isset($config['FilePath'])) {
            throw new FileWriterException("You must specify a valid file path");
        }

        if(!isset($config['Formatter'])) {
            throw new FileWriterException("You must specify a formatter");
        }

        if(isset($config['PerDay']) && $config['PerDay'] == true) {
            $this->perDay = true;
        }

        $this->setFormatter($config['Formatter']);
        $this->setFilePathName($config['FilePath'], $config['FileName']);
    }

    public function setFilePathName($path, $name)
    {
        $this->filePathName .= $path;

        if($this->perDay) {
            $this->filePathName .= (new \DateTime())->format("Y-m-d") . '_';
        }

        $this->filePathName .= $name . self::FILE_SUFFIX;
    }

    public function getFilePathName()
    {
        return $this->filePathName;
    }

    public function write(LogSet $logSet)
    {
        $stream = fopen($this->filePathName, "a+");
        $output = $this->getFormatter()->format($logSet);
        fwrite($stream, $output);
        fclose($stream);
    }
}