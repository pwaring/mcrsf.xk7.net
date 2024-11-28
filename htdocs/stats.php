<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

$sql = "SELECT COUNT(*) AS book_count, section FROM books GROUP BY section";
$sth = $db->prepare($sql);
$sth->execute();
$bookCounts = $sth->fetchAll();

$sql = "SELECT AVG(score) AS mean_score FROM books WHERE score <> '' AND score IS NOT NULL AND section = 'read'";
$sth = $db->prepare($sql);
$sth->execute();
$meanScore = $sth->fetchColumn();

$sql = "SELECT * FROM books WHERE score <> '' AND score IS NOT NULL AND section = 'read' ORDER BY score ASC LIMIT 5";
$sth = $db->prepare($sql);
$sth->execute();
$lowestScoringBooks = $sth->fetchAll();

$sql = "SELECT * FROM books WHERE score <> '' AND score IS NOT NULL AND section = 'read' ORDER BY score DESC LIMIT 5";
$sth = $db->prepare($sql);
$sth->execute();
$highestScoringBooks = $sth->fetchAll();

$twig->display(
    'stats.twig.html',
    [
        'availableSections' => $availableSections,
        'bookCounts' => $bookCounts,
        'meanScore' => $meanScore,
        'lowestScoringBooks' => $lowestScoringBooks,
        'highestScoringBooks' => $highestScoringBooks,
    ]
);
