<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'alquds_academy1');
if (!$conn) {
    echo json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Count juz records
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM juz");
$count = mysqli_fetch_assoc($result)['count'];

// Get all juz records
$result = mysqli_query($conn, "SELECT id, name FROM juz ORDER BY id LIMIT 20");
$juzzes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $juzzes[] = $row;
}

// Check Surah 2 Juzzes
$result = mysqli_query($conn, "SELECT DISTINCT juz_id FROM ayahs WHERE surah_id = 2 ORDER BY juz_id");
$surah2Juzzes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $surah2Juzzes[] = $row['juz_id'];
}

echo json_encode([
    'total_juzzes' => $count,
    'sample_juzzes' => $juzzes,
    'surah_2_juzzes' => $surah2Juzzes,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>
