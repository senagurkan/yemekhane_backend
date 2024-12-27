<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'config.php';


if (!isset($_POST['user_id']) || !isset($_POST['nickname'])) {
    die(json_encode(array("success" => false, "message" => "Gerekli parametreler eksik.")));
}

$user_id = intval($_POST['user_id']);
$nickname = $conn->real_escape_string($_POST['nickname']);

$sql = "UPDATE users SET nickname = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(array("success" => false, "message" => "Sorgu hazırlama hatası: " . $conn->error)));
}

$stmt->bind_param("si", $nickname, $user_id);
if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Takma ad başarıyla güncellendi."));
} else {
    echo json_encode(array("success" => false, "message" => "Güncelleme başarısız: " . $stmt->error));
}

$stmt->close();
$conn->close();
?>
