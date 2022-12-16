<?php

namespace ClebinGames\DotMapParser;

require("src/App.php");

define('CR', "\n");

// read filenames from command line arguments
$options = getopt('', [
    'input:',
    'output:'
]);

App::Run($options);

// show errors
if (App::DidErrorOccur() === true) {
    foreach (App::$errorDetails as $error) {
        echo 'Error: ' . $error . CR;
    }
}
