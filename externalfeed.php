<?php
require_once 'config.php';
require_once 'functions.php';

// OHIKO AKATSA (SSRF):
// ?url=http://127.0.0.1/admin -> file_get_contents($_GET['url'])
// Horrek zerbitzariak bere buruari edo barne sareari deitzea ahalbidetzen du.

// Hemen "iturburu" balio mugatu bat onartuko dugu, eta URL-a guk finkatuko dugu.

$source = $_GET['source'] ?? 'news';

$allowedSources = [
    'news' => 'https://example.com/news.json',
    'blog' => 'https://example.com/blog.json',
];

if (!isset($allowedSources[$source])) {
    die("Iturburu ezezaguna.");
}

$url = $allowedSources[$source];

// Hemen baimendutako helmuga bakarrak daudenez, ez dago SSRF-ik.
$json = @file_get_contents($url);
if ($json === false) {
    die("Kanpoko datuak eskuratzean errorea.");
}

header('Content-Type: application/json; charset=utf-8');
echo $json;
