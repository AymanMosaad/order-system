<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $app->handleRequest(\Illuminate\Http\Request::capture());
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage();
    echo '<br>File: ' . $e->getFile();
    echo '<br>Line: ' . $e->getLine();
}
