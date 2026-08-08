<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'alquds_academy1');
if (!$conn) {
    echo "Connection failed";
    exit;
}

echo "=== DATABASE CHECK ===\n";
echo "1. Total Surahs: ";
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM surahs");
$row = mysqli_fetch_assoc($result);
echo $row['count'] . "\n";

echo "2. Sample Surahs:\n";
$result = mysqli_query($conn, "SELECT id, name_ar FROM surahs LIMIT 5");
while ($row = mysqli_fetch_assoc($result)) {
    echo "   - ID: {$row['id']}, Name: {$row['name_ar']}\n";
}

echo "3. Total Ayahs: ";
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM ayahs");
$row = mysqli_fetch_assoc($result);
echo $row['count'] . "\n";

echo "4. Total Juzzes: ";
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM juz");
$row = mysqli_fetch_assoc($result);
echo $row['count'] . "\n";

mysqli_close($conn);
?>
