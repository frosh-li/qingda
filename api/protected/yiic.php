<?php

function parse_server_params($argv) {
    if (!isset($argv[2])) {
        die("server params file can not be empty.\n");
    }
    if (!is_file($argv[2])) {
        die("server params file must be a file.\n");
    }
    $fp = fopen($argv[2], 'r');
    $fp or die('not found ' . $argv[2]);
    while (!feof($fp)) {
        $line = trim(stream_get_line($fp, 1024, PHP_EOL));
        if (strlen($line) > 0 && $line[0] != '#') {
            $line = str_replace(';', '', $line);
            $params = preg_split("/[\s]+/", $line, 3);
            if (count($params) == 3) {
                $_SERVER[$params[1]] = trim($params[2], '"');
            }
        }
    }
    fclose($fp);
}

parse_server_params($argv);

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);