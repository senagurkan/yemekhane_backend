<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Kullanıcı ID'sini al
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$userId) {
    echo json_encode(array("success" => false, "message" => "Kullanıcı ID'si eksik."));
    $conn->close();
    exit;
}

// Okunmamış bildirim sayısını hesapla
$sql = "
    SELECT COUNT(*) AS unread_count
    FROM Notifications n
    LEFT JOIN Notification_Reads r 
    ON n.id = r.notification_id AND r.user_id = '$userId'
    WHERE r.read_at IS NULL;
";

$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(array("success" => true, "unread_count" => (int)$row["unread_count"]));
} else {
    echo json_encode(array("success" => false, "message" => "Bildirimler alınamadı."));
}

$conn->close();
?>
