<?php

namespace Bastas\Logger\Writer;

use Bastas\Logger\Exception\DatabaseWriterException;
use Bastas\Logger\LogSet;

final class DatabaseWriter extends Writer implements WriterInterface
{
    private $pdoAdapter;

    public function __construct(array $config)
    {
        if (!isset($config['Adapter'])) {
            throw new DatabaseWriterException("You must specify a database adapter");
        }

        if (!$config['Adapter'] instanceof \PDO) {
            throw new DatabaseWriterException("You must provide a valid PDO adapter");
        }

        if (!isset($config['Table'])) {
            throw new DatabaseWriterException("You must specify a table name");
        }

        if (!isset($config['Formatter'])) {
            throw new DatabaseWriterException("You must specify a valid database formatter");
        }

        $this->pdoAdapter = $config['Adapter'];
        $this->setFormatter($config['Formatter'], $config);
    }

    public function write(LogSet $logSet)
    {
        $sqlStatement = $this->pdoAdapter->prepare($this->getFormatter()->format($logSet));
        $sqlStatement->execute();
    }
}