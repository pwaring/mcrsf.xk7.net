<?php

declare(strict_types=1);

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once __DIR__ . '/config.php';

$schema = file_get_contents(__DIR__ . '/books.sql');
$db->exec($schema);

$books = [];

$file = $argv[1];

$reader = IOFactory::createReaderForFile($file);
$reader->setReadDataOnly(false); // Must be false for formatted data to be returned
$reader->setReadEmptyCells(true);
$spreadsheet = $reader->load($file);

// Upcoming
$worksheet = $spreadsheet->getSheetByNameOrThrow('Upcoming');
$highestRow = $worksheet->getHighestDataRow();

for ($row = 2; $row <= $highestRow; $row++) {
    $title = $worksheet->getCell("A{$row}")->getValue();

    if ($title) {
        $authors = $worksheet->getCell("B{$row}")->getValue();
        $pages = $worksheet->getCell("C{$row}")->getValue();
        $publishedYear = $worksheet->getCell("D{$row}")->getValue();
        
        // Date read needs to be converted
        $dateRead = (DateTimeImmutable::createFromFormat(
            'd/m/Y',
            $worksheet->getCell("F{$row}")->getFormattedValue()
        ))->format('Y-m-d');
    
        $section = 'upcoming';
    
        $books[] = [
            'title' => $title,
            'authors' => $authors,
            'pages' => $pages,
            'published_year' => $publishedYear,
            'score' => null,
            'score_type' => null,
            'date_read' => $dateRead,
            'section' => $section,
            'website' => null,
        ];
    }
}

// Read
$worksheet = $spreadsheet->getSheetByNameOrThrow('Read');
$highestRow = $worksheet->getHighestDataRow();

for ($row = 2; $row <= $highestRow; $row++) {
    $title = $worksheet->getCell("A{$row}")->getValue();

    if ($title) {
        $authors = $worksheet->getCell("B{$row}")->getValue();
        $pages = $worksheet->getCell("C{$row}")->getValue();
        $publishedYear = $worksheet->getCell("D{$row}")->getValue();
        
        // Date read needs to be converted
        $dateRead = (DateTimeImmutable::createFromFormat(
            'd/m/Y',
            $worksheet->getCell("F{$row}")->getFormattedValue()
        ))->format('Y-m-d');

        $score = $worksheet->getCell("G{$row}")->getValue();

        if (!$score) {
            $score = null;
        }

        $scoreType = $worksheet->getCell("H{$row}")->getValue();
        $website = $worksheet->getCell("J{$row}")->getValue();
    
        $section = 'read';
    
        $books[] = [
            'title' => $title,
            'authors' => $authors,
            'pages' => $pages,
            'published_year' => $publishedYear,
            'score' => $score,
            'score_type' => $scoreType,
            'date_read' => $dateRead,
            'section' => $section,
            'website' => $website,
        ];
    }
}

// Suggestions
$worksheet = $spreadsheet->getSheetByNameOrThrow('Suggestions');
$highestRow = $worksheet->getHighestDataRow();

for ($row = 2; $row <= $highestRow; $row++) {
    $title = $worksheet->getCell("A{$row}")->getValue();

    if ($title) {
        $authors = $worksheet->getCell("B{$row}")->getValue();
        $pages = $worksheet->getCell("C{$row}")->getValue();
        $publishedYear = $worksheet->getCell("D{$row}")->getValue();
    
        $section = 'suggestions';
    
        $books[] = [
            'title' => $title,
            'authors' => $authors,
            'pages' => $pages,
            'published_year' => $publishedYear,
            'score' => null,
            'score_type' => null,
            'date_read' => null,
            'section' => $section,
            'website' => null,
        ];
    }
}

// Rejected
$worksheet = $spreadsheet->getSheetByNameOrThrow('Rejected');
$highestRow = $worksheet->getHighestDataRow();

for ($row = 2; $row <= $highestRow; $row++) {
    $title = $worksheet->getCell("A{$row}")->getValue();

    if ($title) {
        $authors = $worksheet->getCell("B{$row}")->getValue();
        $pages = $worksheet->getCell("C{$row}")->getValue();
        $publishedYear = $worksheet->getCell("D{$row}")->getValue();
    
        $section = 'rejected';
    
        $books[] = [
            'title' => $title,
            'authors' => $authors,
            'pages' => $pages,
            'published_year' => $publishedYear,
            'score' => null,
            'score_type' => null,
            'date_read' => null,
            'section' => $section,
            'website' => null,
        ];
    }
}

// All sheets read, now insert books into database
$sql = <<<SQL
    INSERT INTO books (
        title,
        authors,
        pages,
        published_year,
        score,
        score_type,
        date_read,
        section,
        website
    ) VALUES (
        :title,
        :authors,
        :pages,
        :published_year,
        :score,
        :score_type,
        :date_read,
        :section,
        :website
    )
SQL;
$sth = $db->prepare($sql);

foreach ($books as $book) {
    $sth->execute($book);
}
