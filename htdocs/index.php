<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$section = $_GET['section'] ?? 'upcoming';

header("Location: {$section}.php");
exit;
