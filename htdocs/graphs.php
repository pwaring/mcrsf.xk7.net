<?php

declare(strict_types=1);

use Goat1000\SVGGraph\SVGGraph;

require_once __DIR__ . '/../config.php';

$sql = "SELECT date_read, score FROM books WHERE section = 'read' AND score <> '' AND score IS NOT NULL ORDER BY date_read ASC";
$sth = $db->prepare($sql);
$sth->execute();
$dateReadScores = [];
while ($row = $sth->fetch()) {
    $dateReadScores[$row['date_read']] = $row['score'];
}

$settings = array(
  'back_colour'       => '#eee',
  'stroke_colour'     => '#000',
  'back_stroke_width' => 0,
  'back_stroke_colour'=> '#eee',
  'axis_colour'       => '#333',
  'axis_overlap'      => 2,
  'axis_font'         => 'Arial',
  'axis_font_size'    => 10,
  'grid_colour'       => '#666',
  'label_colour'      => '#000',
  'pad_right'         => 20,
  'pad_left'          => 20,
  'link_base'         => '/',
  'link_target'       => '_top',
  'fill_under'        => array(true, false),
  'marker_size'       => 3,
  'marker_type'       => array('circle', 'square'),
  'marker_colour'     => array('blue', 'red'),
  'show_label_h'  => false,
  'best_fit'          => 'curve',
  'best_fit_width'    => 5,
);

$colours = array(
  array('red', 'yellow')
);
 
$graph = new SVGGraph(600, 200, $settings);

$graph->colours($colours);
$graph->values($dateReadScores);
$dateReadScoresSvg = $graph->fetch('LineGraph');

$sql = <<<SQL
    SELECT
        AVG(score) AS avg_score,
        strftime('%Y', date_read) AS year_read
    FROM
        books
    WHERE
        section = 'read'
        AND score <> ''
        AND score IS NOT NULL
    GROUP BY strftime('%Y', date_read)
    ORDER BY strftime('%Y', date_read) ASC
SQL;
$sth = $db->prepare($sql);
$sth->execute();
$yearScores = [];
while ($row = $sth->fetch()) {
    $yearScores[$row['year_read']] = $row['avg_score'];
}

$settings = array(
  'back_colour'       => '#eee',
  'stroke_colour'     => '#000',
  'back_stroke_width' => 0,
  'back_stroke_colour'=> '#eee',
  'axis_colour'       => '#333',
  'axis_overlap'      => 2,
  'axis_font'         => 'Arial',
  'axis_font_size'    => 10,
  'grid_colour'       => '#666',
  'label_colour'      => '#000',
  'pad_right'         => 20,
  'pad_left'          => 20,
  'link_base'         => '/',
  'link_target'       => '_top',
  'fill_under'        => array(true, false),
  'marker_size'       => 3,
  'marker_type'       => array('circle', 'square'),
  'marker_colour'     => array('blue', 'red'),
  'show_label_h'  => true,
  'best_fit'          => 'curve',
  'best_fit_width'    => 5,
  'axis_min_h'        => 2012,
);

$colours = array(
  array('red', 'yellow')
);

$graph = new SVGGraph(600, 200, $settings);

$graph->colours($colours);
$graph->values($yearScores);
$yearReadScoresSvg = $graph->fetch('LineGraph');

$sql = <<<SQL
    SELECT
        AVG(score) AS avg_score,
        pages
    FROM
        books
    WHERE
        section = 'read'
        AND score <> ''
        AND score IS NOT NULL
    GROUP BY pages
    ORDER BY pages ASC
SQL;
$sth = $db->prepare($sql);
$sth->execute();
$pageScores = [];
while ($row = $sth->fetch()) {
    $pageScores[$row['pages']] = $row['avg_score'];
}

$settings = array(
  'back_colour'       => '#eee',
  'stroke_colour'     => '#000',
  'back_stroke_width' => 0,
  'back_stroke_colour'=> '#eee',
  'axis_colour'       => '#333',
  'axis_overlap'      => 2,
  'axis_font'         => 'Arial',
  'axis_font_size'    => 10,
  'grid_colour'       => '#666',
  'label_colour'      => '#000',
  'pad_right'         => 20,
  'pad_left'          => 20,
  'link_base'         => '/',
  'link_target'       => '_top',
  'fill_under'        => array(true, false),
  'marker_size'       => 3,
  'marker_type'       => array('circle', 'square'),
  'marker_colour'     => array('blue', 'red'),
  'show_label_h'  => false,
  'best_fit'          => 'straight',
  'best_fit_width'    => 5,
);

$colours = array(
  array('red', 'yellow')
);
 
$graph = new SVGGraph(600, 200, $settings);

$graph->colours($colours);
$graph->values($pageScores);
$pageScoresSvg = $graph->fetch('LineGraph');

$twig->display(
    'graphs.twig',
    [
        'dateReadScoresSvg' => $dateReadScoresSvg,
        'yearReadScoresSvg' => $yearReadScoresSvg,
        'pageScoresSvg' => $pageScoresSvg,
    ]
);
