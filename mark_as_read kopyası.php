<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';

// Parametreleri al
$userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
$notificationId = isset($_POST['notification_id']) ? $_POST['notification_id'] : null;

// Parametre kontrolü
if ($userId == null || $notificationId == null) {
    echo json_encode(array("success" => false, "message" => "Eksik parametreler"));
    exit;
}

// Duyuru okundu olarak işaretleme SQL sorgusu
$sql = "INSERT INTO Notification_Reads (user_id, notification_id, read_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE read_at = NOW()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $notificationId);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Duyuru okundu olarak işaretlendi"));
} else {
    echo json_encode(array("success" => false, "message" => "Duyuru okundu olarak işaretlenemedi"));
}

$stmt->close();
$conn->close();
?>
