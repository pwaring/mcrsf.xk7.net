<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$books = [];

$title = $_GET['title'] ?? '';
$authors = $_GET['authors'] ?? '';
$minimumScore = $_GET['minimum_score'] ?? '';
$maximumScore = $_GET['maximum_score'] ?? '';

$whereClauses = [];
$placeholders = [];

if (strlen($title) > 0) {
    $whereClauses[] = "title LIKE '%' || :title || '%'";
    $placeholders['title'] = $title;
}

if (strlen($authors) > 0) {
    $whereClauses[] = "authors LIKE '%' || :authors || '%'";
    $placeholders['authors'] = $authors;
}

if (strlen($minimumScore) > 0) {
    $whereClauses[] = "score >= :minimum_score";
    $placeholders['minimum_score'] = $minimumScore;
}

if (strlen($maximumScore) > 0) {
    $whereClauses[] = "score <= :maximum_score";
    $placeholders['maximum_score'] = $maximumScore;
}

if (count($whereClauses) > 0) {
    $sql = "SELECT * FROM books WHERE ";
    $sql .= implode(' AND ', $whereClauses);
    $sth = $db->prepare($sql);
    $sth->execute($placeholders);
    $books = $sth->fetchAll();
}

$twig->display(
    'search.twig.html',
    [
        'books' => $books,
        'availableSections' => $availableSections,
        'title' => $title,
        'authors' => $authors,
        'minimumScore' => $minimumScore,
        'maximumScore' => $maximumScore,
    ]
);
