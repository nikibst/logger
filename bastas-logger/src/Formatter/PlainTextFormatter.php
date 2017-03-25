<?php

namespace Bastas\Logger\Formatter;

use Bastas\Logger\LogSet;

final class PlainTextFormatter implements FormatterInterface
{
    public function __construct(array $config = [])
    {
    }

    //TODO For some reason the output file has two (empty) lines at the end of the file.
    public function format(LogSet $logSet)
    {
        $output = '';
        $output .= 'Timestamp: ' . $logSet->getTimeStamp() . PHP_EOL;
        $output .= 'Severity Code: ' . $logSet->getSeverityCode() . PHP_EOL;
        $output .= 'Severty Literal: ' . $logSet->getSeverityLiteral() . PHP_EOL;

        foreach ($logSet->getLogSetInput() as $input) {
            if (is_object($logSet->$input)) {
                $output .= $input . ': ' . $logSet->$input->__toString() . PHP_EOL;
            } else {
                $output .= $input . ': ' . $logSet->$input . PHP_EOL;
            }
        }

        $output .= 'Extra Information: ' . json_encode($logSet->getExtraInfo()) . PHP_EOL;
        $output .= PHP_EOL;

        return $output;
    }
}