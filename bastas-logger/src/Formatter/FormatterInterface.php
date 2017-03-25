<?php

namespace Bastas\Logger\Formatter;

use Bastas\Logger\LogSet;

interface FormatterInterface
{
    public function __construct(array $config = []);
    public function format(LogSet $logSet);
}