<?php
/**
 * Created by PhpStorm.
 * User: nikitas
 * Date: 25/2/2017
 * Time: 09:39
 */

namespace Bastas\Logger\Writer;


use Bastas\Logger\LogSet;

interface WriterInterface
{
    public function __construct(array $config);
    public function write(LogSet $logSet);
}