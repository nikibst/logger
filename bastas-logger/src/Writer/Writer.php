<?php
/**
 * Created by PhpStorm.
 * User: nikitas
 * Date: 25/2/2017
 * Time: 09:40
 */

namespace Bastas\Logger\Writer;

//TODO: Rename to AbstractWriter
class Writer
{
    private $writers = [];
    private $formatter;

    public function addWriter(WriterInterface $writer)
    {
        $this->writers[] = $writer;
    }

    public function getWriters()
    {
        return $this->writers;
    }

    public static function writerFactory($writerName, $config)
    {
        $writer = new $writerName($config);
        return $writer;
    }

    public function setFormatter($formatterName, $config = [])
    {
        $this->formatter = new $formatterName($config);
    }

    public function getFormatter()
    {
        return $this->formatter;
    }
}