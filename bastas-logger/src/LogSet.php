<?php

namespace Bastas\Logger;

final class LogSet
{
    private $counter;
    private $logSetInput;
    private $severityCode;
    private $severityLiteral;
    private $extraInfo;
    private $timestamp;

    public static $logLevelLiteralsToCodes = [
        'emergency' => 0,
        'alert'     => 1,
        'critical'  => 2,
        'error'     => 3,
        'warning'   => 4,
        'notice'    => 5,
        'info'      => 6,
        'debug'     => 7,
    ];

    public function __construct($logSetInput)
    {
        $this->logSetInput = $logSetInput;
        $this->setPropertyValues();
        $this->setTimeStamp();
        $this->setCount(count($this->logSetInput));
    }

    public function setPropertyValues($values = [])
    {
        if(empty($values)) {
            foreach($this->logSetInput as $property) {
                $this->$property = null;
            }
        } else {
            for($i = 0; $i < count($this->logSetInput); ++$i) {
                $this->{$this->logSetInput[$i]} = $values[$i];
            }
        }
    }

    public function getLogSetInput()
    {
        return $this->logSetInput;
    }

    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    public function setExtraInfo(array $extraInfo)
    {
        $this->extraInfo = $extraInfo;
    }

    public function getCount()
    {
        return $this->counter;
    }

    public function setCount(int $counter)
    {
        $this->counter = $counter;
    }

    public function getSeverityCode()
    {
        return $this->severityCode;
    }

    private function setSeverityCode($severityLiteral)
    {
        $this->severityCode =  self::$logLevelLiteralsToCodes[$severityLiteral];
    }

    public function getSeverityLiteral()
    {
        return $this->severityLiteral;
    }

    public function setSeverityLiteral($severityLiteral)
    {
        $this->severityLiteral = $severityLiteral;
        $this->setSeverityCode($severityLiteral);
    }

    public function getTimeStamp()
    {
        return $this->timestamp;
    }

    public function setTimeStamp()
    {
        $this->timestamp = (new \DateTime())->format("Y-m-d h:i:s");
    }
}