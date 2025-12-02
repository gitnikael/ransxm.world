<?php
$likesFile = 'views.txt';

if (!file_exists($likesFile)) {
    file_put_contents($likesFile, "0");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $likes = intval(file_get_contents($likesFile));
    echo json_encode(['likes' => $likes]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $likes = intval(file_get_contents($likesFile)) + 1;
    file_put_contents($likesFile, $likes);
    echo json_encode(['likes' => $likes]);
    exit;
}
?>
