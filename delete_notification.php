<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';

// Parametreleri al
$notification_id = isset($_POST['notification_id']) ? $_POST['notification_id'] : null;

// Hatalı istek durumu
if ($notification_id == null) {
    echo json_encode(array("success" => false, "message" => "Eksik parametreler."));
    exit();
}

// Transaction başlat
$conn->begin_transaction();

try {
    // Notification_Reads tablosundan sil
    $deleteReadsSql = "DELETE FROM Notification_Reads WHERE notification_id = ?";
    $stmtReads = $conn->prepare($deleteReadsSql);
    $stmtReads->bind_param("i", $notification_id);
    $stmtReads->execute();

    // Notifications tablosundan sil
    $deleteNotificationSql = "DELETE FROM Notifications WHERE id = ?";
    $stmtNotifications = $conn->prepare($deleteNotificationSql);
    $stmtNotifications->bind_param("i", $notification_id);
    $stmtNotifications->execute();

    // İşlem başarılıysa commit
    $conn->commit();

    echo json_encode(array("success" => true, "message" => "Duyuru başarıyla silindi."));

    $stmtReads->close();
    $stmtNotifications->close();
} catch (Exception $e) {
    // Bir hata oluşursa işlemi geri al
    $conn->rollback();
    echo json_encode(array("success" => false, "message" => "Duyuru silinirken hata oluştu: " . $e->getMessage()));
}

$conn->close();
?>
