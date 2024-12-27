<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Parametreleri al
$message = isset($_POST['message']) ? $_POST['message'] : null;
$adminId = isset($_POST['admin_id']) ? $_POST['admin_id'] : null;

// Parametrelerin doğruluğunu kontrol et
if ($message == null || $adminId == null) {
    echo json_encode(array("success" => false, "message" => "Eksik parametreler"));
    exit();
}

// Duyuruyu eklemek için sorguyu oluştur
$sql = "INSERT INTO Notifications (admin_id, message, sent_at) VALUES ('$adminId', '$message', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("success" => true, "message" => "Duyuru başarıyla eklendi"));
} else {
    echo json_encode(array("success" => false, "message" => "Duyuru eklenirken hata oluştu: " . $conn->error));
}

$conn->close();
?>
