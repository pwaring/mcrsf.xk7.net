<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$books = [];

$title = $_GET['title'] ?? '';

if (!empty($title)) {
    $sql = "SELECT * FROM books WHERE title LIKE '%' || :title || '%'";
    $sth = $db->prepare($sql);
    $sth->bindValue('title', $title, PDO::PARAM_STR);
    $sth->execute();
    $books = $sth->fetchAll();
}

$twig->display(
    'search.twig.html',
    [
        'books' => $books,
        'availableSections' => $availableSections,
        'title' => $title,
    ]
);
