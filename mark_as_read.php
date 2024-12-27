<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// POST parametrelerini al ve doğrula
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
$notificationId = isset($_POST['notification_id']) ? (int)$_POST['notification_id'] : null;

if (!$userId || !$notificationId) {
    echo json_encode(array("success" => false, "message" => "Eksik parametreler."));
    $conn->close();
    exit;
}

// Bildirimi okundu olarak işaretle
$sql = "
    INSERT INTO Notification_Reads (user_id, notification_id, read_at)
    VALUES ('$userId', '$notificationId', NOW())
    ON DUPLICATE KEY UPDATE read_at = NOW();
";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("success" => true, "message" => "Bildirim okundu olarak işaretlendi."));
} else {
    echo json_encode(array("success" => false, "message" => $conn->error));
}

$conn->close();
?>
