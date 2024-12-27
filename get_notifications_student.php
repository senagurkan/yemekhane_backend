<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Kullanıcı ID'sini al
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if (!$userId) {
    echo json_encode(array("success" => false, "message" => "User ID is required"));
    $conn->close();
    exit;
}

// Duyurular ve okundu durumu bilgisi
$sql = "
    SELECT 
        n.id,
        n.admin_id,
        n.message,
        n.sent_at,
        nr.read_at
    FROM Notifications n
    LEFT JOIN Notification_Reads nr ON n.id = nr.notification_id AND nr.user_id = $userId
    ORDER BY n.sent_at DESC
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $notifications = array();
    while($row = $result->fetch_assoc()) {
        $notifications[] = array(
            "id" => (int)$row['id'],
            "admin_id" => (int)$row['admin_id'],
            "message" => $row['message'],
            "sent_at" => $row['sent_at'],
            "read_at" => $row['read_at'] // NULL ise okunmamış, değer varsa okunmuş
        );
    }
    echo json_encode(array("success" => true, "notifications" => $notifications));
} else {
    echo json_encode(array("success" => false, "message" => "No notifications found"));
}

$conn->close();
?>
