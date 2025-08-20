<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$sql = 'SELECT * FROM books WHERE section = :section ORDER BY date_read ASC';

$sth = $db->prepare($sql);
$sth->bindValue('section', 'upcoming', PDO::PARAM_STR);
$sth->execute();
$books = $sth->fetchAll();

$twig->display(
    'upcoming.twig',
    [
        'books' => $books,
    ]
);
