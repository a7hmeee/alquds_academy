<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'alquds_academy1');
if (!$conn) {
    echo json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Get Juzzes for Surah 2 exactly like API does
$stmt = $conn->prepare("
    SELECT DISTINCT juz_id FROM ayahs WHERE surah_id = 2
");
$stmt->execute();
$result = $stmt->get_result();
$juzIds = [];
while ($row = $result->fetch_assoc()) {
    $juzIds[] = $row['juz_id'];
}

echo "Juz IDs for Surah 2: " . json_encode($juzIds) . "\n";

// Now get Juz data
if (!empty($juzIds)) {
    $placeholders = implode(',', $juzIds);
    $query = "SELECT id, name FROM juz WHERE id IN ($placeholders) ORDER BY id ASC";
    echo "Query: $query\n";
    
    $result = mysqli_query($conn, $query);
    $juzzes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $juzzes[] = [
            'id' => (int)$row['id'],
            'number' => (int)$row['id'],
            'name_ar' => $row['name'],
        ];
    }
    
    echo json_encode($juzzes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);
?>
