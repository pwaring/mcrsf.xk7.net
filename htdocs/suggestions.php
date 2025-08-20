<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$sql = 'SELECT * FROM books WHERE section = :section ORDER BY date_read DESC';

$sth = $db->prepare($sql);
$sth->bindValue('section', 'suggestions', PDO::PARAM_STR);
$sth->execute();
$books = $sth->fetchAll();

$twig->display(
    'suggestions.twig',
    [
        'books' => $books,
    ]
);
