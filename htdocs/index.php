<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$section = $_GET['section'] ?? 'upcoming';

if (!in_array($section, $availableSections, true)) {
    $section = 'upcoming';
}

$sql = 'SELECT * FROM books WHERE section = :section';
$sth = $db->prepare($sql);
$sth->bindValue('section', $section, PDO::PARAM_STR);
$sth->execute();
$books = $sth->fetchAll();

$twig->display(
    'index.twig.html',
    [
        'books' => $books,
        'availableSections' => $availableSections,
        'section' => $section,
    ]
);
