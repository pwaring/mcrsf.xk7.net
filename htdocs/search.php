<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../config.php';

$books = [];

$request = Request::createFromGlobals();

$title = $request->query->getString('title');
$authors = $request->query->getString('authors');
$minimumScore = $request->query->getString('minimum_score');
$maximumScore = $request->query->getString('maximum_score');
$minimumLength = $request->query->getString('minimum_length');
$maximumLength = $request->query->getString('maximum_length');

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

if (strlen($minimumLength) > 0) {
    $whereClauses[] = "pages >= :minimum_length";
    $placeholders['minimum_length'] = $minimumLength;
}

if (strlen($maximumLength) > 0) {
    $whereClauses[] = "pages <= :maximum_length";
    $placeholders['maximum_length'] = $maximumLength;
}

if (count($whereClauses) > 0) {
    $sql = "SELECT * FROM books WHERE ";
    $sql .= implode(' AND ', $whereClauses);
    $sth = $db->prepare($sql);
    $sth->execute($placeholders);
    $books = $sth->fetchAll();
}

$twig->display(
    'search.twig',
    [
        'books' => $books,
        'title' => $title,
        'authors' => $authors,
        'minimumScore' => $minimumScore,
        'maximumScore' => $maximumScore,
        'minimumLength' => $minimumLength,
        'maximumLength' => $maximumLength,
    ]
);
