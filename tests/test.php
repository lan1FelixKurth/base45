<?php 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload


$string = "Hello World!";
$base45String = \LAN1\Base45::encode($string);
echo $base45String . PHP_EOL;
$plainText = \LAN1\Base45::decode($base45String);
echo $plainText . PHP_EOL;
