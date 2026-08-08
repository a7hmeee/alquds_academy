<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'alquds_academy1');
if (!$conn) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Test 1: Get distinct juz_ids for Surah 2
echo "=== Test 1: DISTINCT juz_id for Surah 2 ===\n";
$result = mysqli_query($conn, "SELECT DISTINCT juz_id FROM ayahs WHERE surah_id = 2");
if (!$result) {
    echo "Error: " . mysqli_error($conn) . "\n";
} else {
    $juzIds = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $juzIds[] = $row['juz_id'];
    }
    echo json_encode($juzIds) . "\n";
}

// Test 2: Get all Juz data (not filtered)
echo "\n=== Test 2: All JUZ (no filter) ===\n";
$result = mysqli_query($conn, "SELECT id, name FROM juz ORDER BY id LIMIT 5");
$juzzes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $juzzes[] = ['id' => (int)$row['id'], 'name' => $row['name']];
}
echo json_encode($juzzes, JSON_PRETTY_PRINT) . "\n";

// Test 3: Laravel duplicated SELECT query
echo "\n=== Test 3: Using WHERE IN with juz_ids ===\n";
$placeholders = implode(',', [4, 5, 6]);
$result = mysqli_query($conn, "SELECT id, name FROM juz WHERE id IN ($placeholders) ORDER BY id ASC");
$juzzes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $juzzes[] = [
        'id' => (int)$row['id'],
        'number' => (int)$row['id'],
        'name_ar' => $row['name']
    ];
}
echo json_encode($juzzes, JSON_PRETTY_PRINT) . "\n";

// Test 4: What does distinct without grouping return?
echo "\n=== Test 4: Check all ayahs for Surah 2 juz_id ===\n";
$result = mysqli_query($conn, "SELECT juz_id FROM ayahs WHERE surah_id = 2 LIMIT 10");
$values = [];
while ($row = mysqli_fetch_assoc($result)) {
    $values[] = $row['juz_id'];
}
echo "First 10 juz_ids: " . json_encode($values) . "\n";

mysqli_close($conn);
?>
