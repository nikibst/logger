<?php

namespace Bastas\Logger\Formatter;

use Bastas\Logger\LogSet;

final class DbDefaultFormatter implements FormatterInterface
{
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function format(LogSet $logSet)
    {
        $fieldNames = [];
        $fieldValues = [];

        $sqlStatement = 'INSERT INTO ' . $this->config['Table'] . ' (';
        $sqlStatement .= '`timestamp`,`severity_code`,`severity_literal`,`extra_information`,';

        foreach ($logSet->getLogSetInput() as $input) {
            $fieldNames[] = "`" . $input . "`";
            if (is_object($logSet->$input)) {
                $fieldValues[] = "'" . $logSet->$input->__toString() . "'";
            } else {
                $fieldValues[] = "'" . $logSet->$input . "'";
            }
        }

        $sqlStatement .= implode($fieldNames, ",") . ')';
        $sqlStatement .= ' VALUES (';
        $sqlStatement .= "'" . $logSet->getTimeStamp() . "',";
        $sqlStatement .= $logSet->getSeverityCode() . ',';
        $sqlStatement .= "'" . $logSet->getSeverityLiteral() . "',";
        $sqlStatement .= ((empty($logSet->getExtraInfo())) ? 'null' : "'" .
                            json_encode($logSet->getExtraInfo()) . "'") . ",";
        $sqlStatement .= implode($fieldValues, ",") . ');';

        return $sqlStatement;

    }
}