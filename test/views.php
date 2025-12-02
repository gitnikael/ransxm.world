<?php
$viewsFile = 'views.txt';

if (!file_exists($viewsFile)) {
    file_put_contents($viewsFile, "0");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $views = intval(file_get_contents($viewsFile));
    echo json_encode(['views' => $views]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $views = intval(file_get_contents($viewsFile)) + 1;
    file_put_contents($viewsFile, $views);
    echo json_encode(['views' => $views]);
    exit;
}
?>
