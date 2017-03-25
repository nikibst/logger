<?php
/**
 * Created by PhpStorm.
 * User: nikitas
 * Date: 25/2/2017
 * Time: 09:45
 */

namespace Bastas\Logger;


use Bastas\Logger\Exception\LoggerException;
use Bastas\Logger\Writer\Writer;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

//TODO Separation of concerns break. This class does more than one thing.
final class Logger implements LoggerInterface
{
    private static $loggersCollection = [];
    private $logSetInstance = null;
    private $writers = null;

    public static function initiateLoggers(array $config)
    {
        if(isset(self::$loggersCollection[$config['LoggerName']])) {
            return self::$loggersCollection[$config['LoggerName']];
        }

        $logger = new Logger();

        $logger->setLogSet($config['LoggerProperties']);
        self::registerLogger($config['LoggerName'], $logger);

        if(!isset($config['writers']) ||empty($config['writers'])) {
            throw new LoggerException("At least one writer must be specified.");
        }

        $writer = new Writer();

        foreach($config['writers'] as $writerName => $configurations) {
            $writer->addWriter(Writer::writerFactory($writerName, $configurations));
        }

        $logger->writers = $writer->getWriters();

        return $logger;
    }

    private function setLogSet($logSet)
    {
        $set = new LogSet($logSet);
        $this->logSetInstance = $set;
    }

    private function getLogSet()
    {
        return $this->logSetInstance;
    }

    private static function registerLogger($loggerName, $logger)
    {
        self::$loggersCollection[$loggerName] = $logger;
    }

    public static function unregisterLogger($loggerName)
    {
        unset(self::$loggersCollection[$loggerName]);
    }

    private function write(Writer $writer, LogSet $logSet)
    {
        $writer->write($logSet);
    }

    public function emergency($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::EMERGENCY, $valueSet, $extraInfo);
    }

    public function alert($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::ALERT, $valueSet, $extraInfo);
    }

    public function critical($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::CRITICAL, $valueSet, $extraInfo);
    }

    public function error($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::ERROR, $valueSet, $extraInfo);
    }

    public function warning($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::WARNING, $valueSet, $extraInfo);
    }

    public function notice($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::NOTICE, $valueSet, $extraInfo);
    }

    public function info($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::INFO, $valueSet, $extraInfo);
    }

    public function debug($valueSet, array $extraInfo = array())
    {
        $this->processLog(LogLevel::DEBUG, $valueSet, $extraInfo);
    }

    public function log($severityLiteral, $valueSet, array $extraInfo = [])
    {
        if(!isset(LogSet::$logLevelLiteralsToCodes[$severityLiteral])) {
            throw new InvalidArgumentException("You must provide a Log Level according to PSR-3 specification.");
        }

        if(is_array($valueSet)) {
            if(count($valueSet) !== $this->getLogSet()->getCount()) {
                throw new LoggerException("You must provide the exact number of arguments as defined in logset");
            }
        } else {
            //TODO Support default string message
        }

        $this->processLog($severityLiteral, $valueSet, $extraInfo);
    }

    private function processLog($severityLiteral, $valueSet, $extraInfo)
    {
        $this->getLogSet()->setSeverityLiteral($severityLiteral);
        $this->getLogSet()->setPropertyValues($valueSet);
        $this->getLogSet()->setExtraInfo($extraInfo);

        foreach($this->writers as $writer) {
            $this->write($writer, $this->getLogSet());
        }
    }
}