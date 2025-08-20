<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$sql = "SELECT COUNT(*) AS book_count, section FROM books GROUP BY section";
$sth = $db->prepare($sql);
$sth->execute();
$bookCounts = $sth->fetchAll();
$bookCountsTotal = array_sum(array_column($bookCounts, 'book_count'));

$sql = "SELECT AVG(score) AS mean_score FROM books WHERE score <> '' AND score IS NOT NULL AND section = 'read'";
$sth = $db->prepare($sql);
$sth->execute();
$meanScore = $sth->fetchColumn();

$sql = "SELECT AVG(pages) AS mean_pages FROM books WHERE pages > 0 AND section = 'read'";
$sth = $db->prepare($sql);
$sth->execute();
$meanPages = $sth->fetchColumn();

$sql = "SELECT * FROM books WHERE score <> '' AND score IS NOT NULL AND section = 'read' ORDER BY score ASC LIMIT 5";
$sth = $db->prepare($sql);
$sth->execute();
$lowestScoringBooks = $sth->fetchAll();

$sql = "SELECT * FROM books WHERE score <> '' AND score IS NOT NULL AND section = 'read' ORDER BY score DESC LIMIT 5";
$sth = $db->prepare($sql);
$sth->execute();
$highestScoringBooks = $sth->fetchAll();

$sql = "SELECT * FROM books WHERE section = 'read' AND pages > 0 ORDER BY pages ASC LIMIT 5";
$sth = $db->prepare($sql);
$sth->execute();
$shortestBooks = $sth->fetchAll();

$sql = "SELECT * FROM books WHERE section = 'read' AND pages > 0 ORDER BY pages DESC LIMIT 5";
$sth = $db->prepare($sql);
$sth->execute();
$longestBooks = $sth->fetchAll();

$twig->display(
    'stats.twig',
    [
        'bookCounts' => $bookCounts,
        'bookCountsTotal' => $bookCountsTotal,
        'meanScore' => $meanScore,
        'meanPages' => $meanPages,
        'lowestScoringBooks' => $lowestScoringBooks,
        'highestScoringBooks' => $highestScoringBooks,
        'shortestBooks' => $shortestBooks,
        'longestBooks' => $longestBooks,
    ]
);
