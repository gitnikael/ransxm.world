<?php
// views.php
header("Content-Type: application/json; charset=utf-8");
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$viewsFile = __DIR__ . DIRECTORY_SEPARATOR . 'views.txt';

if (!file_exists($viewsFile)) {
    file_put_contents($viewsFile, "0", LOCK_EX);
}

function read_views($path) {
    $fh = fopen($path, 'c+');
    if (!$fh) return 0;
    if (flock($fh, LOCK_SH)) {
        clearstatcache(true, $path);
        $contents = stream_get_contents($fh);
        flock($fh, LOCK_UN);
        fclose($fh);
        $val = intval(trim($contents));
        return $val;
    } else {
        fclose($fh);
        return 0;
    }
}

function increment_views($path) {
    $fh = fopen($path, 'c+');
    if (!$fh) return 0;
    if (flock($fh, LOCK_EX)) {
        rewind($fh);
        $contents = stream_get_contents($fh);
        $current = intval(trim($contents));
        $current++;
        rewind($fh);
        ftruncate($fh, 0);
        fwrite($fh, (string)$current);
        fflush($fh);
        flock($fh, LOCK_UN);
        fclose($fh);
        return $current;
    } else {
        fclose($fh);
        return 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $views = read_views($viewsFile);
    echo json_encode(['views' => $views]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $views = increment_views($viewsFile);
    echo json_encode(['views' => $views]);
    exit;
}

echo json_encode(['views' => read_views($viewsFile)]);
exit;
?>
?
