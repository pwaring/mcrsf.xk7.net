<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

error_reporting(E_ALL);
ini_set('display_errors', '1');

set_error_handler(
    function ($severity, $message, $file, $line) {
        throw new \ErrorException($message, $severity, $severity, $file, $line);
    }
);

require_once __DIR__ . '/vendor/autoload.php';

$dsn = 'sqlite:' . __DIR__ . '/htdocs/books.db';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];
$db = new PDO($dsn, null, null, $options);

$availableSections = [
    'upcoming',
    'read',
    'suggestions',
    'rejected',
];

$loader = new FilesystemLoader(__DIR__ . '/templates/');
$twig = new Environment(
    $loader,
    [
        'cache' => false,
        'strict_variables' => true,
    ]
);
