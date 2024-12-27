<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


$sql = "SELECT date FROM menu";
$result = $conn->query($sql);

$dates = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['date'];
    }
}

echo json_encode($dates);

$conn->close();
?>
