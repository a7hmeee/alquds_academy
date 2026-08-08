<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/recordings/surah/2/juz');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo json_encode(json_decode($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_ARABIC);
?>
