<?php
use Symfony\Component\Console\Application;

$console = new Application();

require_once APP_DIR . '/configs/console.php';

// Configure styles
$output = new Symfony\Component\Console\Output\ConsoleOutput();
$style = new Symfony\Component\Console\Formatter\OutputFormatterStyle('blue', null, array('bold'));
$output->getFormatter()->setStyle('header', $style);

$status = $console->run(null, $output);

exit($status);
