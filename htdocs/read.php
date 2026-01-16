<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../config.php';

$request = Request::createFromGlobals();

$orderColumn = $request->query->getString('order_column', 'date_read');
$orderDirection = $request->query->getString('order_direction', 'desc');

if (!in_array($orderColumn, $allowedOrderColumns, true)) {
    $response = new Response(
        'Invalid order column',
        Response::HTTP_BAD_REQUEST
    );

    $response->send();
    exit;
}

if (!in_array($orderDirection, $allowedOrderDirections, true)) {
    $response = new Response(
        'Invalid order direction',
        Response::HTTP_BAD_REQUEST
    );

    $response->send();
    exit;
}

$orderDirection = strtoupper($orderDirection);

$sql = "SELECT * FROM books WHERE section = :section ORDER BY $orderColumn $orderDirection";

$sth = $db->prepare($sql);
$sth->bindValue('section', 'read', PDO::PARAM_STR);
$sth->execute();
$books = $sth->fetchAll();

$twig->display(
    'read.twig',
    [
        'books' => $books,
    ]
);
