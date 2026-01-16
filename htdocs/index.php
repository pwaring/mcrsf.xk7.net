<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../config.php';

$request = Request::createFromGlobals();

$section = $request->query->getString('section', 'upcoming');

header("Location: {$section}.php");
exit;
