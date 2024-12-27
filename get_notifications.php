<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Verileri al
$sql = "SELECT * FROM Notifications ORDER BY sent_at DESC"; // Duyuruları tarihe göre sırala
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $notifications = array();
    while($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    echo json_encode(array("success" => true, "notifications" => $notifications));
} else {
    echo json_encode(array("success" => false, "message" => "No notifications found"));
}

$conn->close();
?>
