<?php

$message = getopt("f:")['f'];
$file = '/app/log.txt';

bigDataProcessStart($message, $file);

function bigDataProcessStart(string $message, string $file): void
{   
    sleep(2);
    file_put_contents($file, $message.PHP_EOL, FILE_APPEND | LOCK_EX);
}