<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'alquds_academy1');
if (!$conn) {
    echo "Connection failed";
    exit;
}

// Set UTF-8
mysqli_set_charset($conn, 'utf8mb4');

echo "=== Testing Ayahs Data ===\n";

// Test 1: Get one ayah
echo "Test 1: Get one ayah from Surah 2\n";
$result = mysqli_query($conn, "SELECT id, surah_id, juz_id, ayah_number, text FROM ayahs WHERE surah_id = 2 LIMIT 1");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "ID: " . $row['id'] . "\n";
    echo "SURAH: " . $row['surah_id'] . "\n";
    echo "JUZ: " . $row['juz_id'] . "\n";
    echo "AYAH NUM: " . $row['ayah_number'] . "\n";
    echo "TEXT LENGTH: " . strlen($row['text']) . "\n";
    echo "TEXT (first 50 chars): " . substr($row['text'], 0, 50) . "\n";
}

// Test 2: Get ayahs in Juz 5
echo "\n\nTest 2: Surah 2, Juz 5 Ayahs\n";
$result = mysqli_query($conn, "SELECT id, ayah_number FROM ayahs WHERE surah_id = 2 AND juz_id = 5 ORDER BY ayah_number");
$count = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    echo "Ayah " . $row['ayah_number'] . "\n";
}
echo "Total: $count ayahs\n";

// Test 3: Check table encoding
echo "\n\nTest 3: Table Encoding\n";
$result = mysqli_query($conn, "SHOW CREATE TABLE ayahs");
$row = mysqli_fetch_assoc($result);
echo $row['Create Table'] . "\n";

mysqli_close($conn);
?>
