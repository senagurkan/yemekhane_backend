<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'config.php';


if (!isset($_POST['user_id']) || !isset($_POST['color'])) {
    die(json_encode(array("success" => false, "message" => "Gerekli parametreler eksik.")));
}

$user_id = intval($_POST['user_id']);
$color = $conn->real_escape_string($_POST['color']);

$sql = "UPDATE users SET color = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(array("success" => false, "message" => "Sorgu hazırlama hatası: " . $conn->error)));
}

$stmt->bind_param("si", $color, $user_id);
if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "İkon rengi başarıyla güncellendi."));
} else {
    echo json_encode(array("success" => false, "message" => "Güncelleme başarısız: " . $stmt->error));
}

$stmt->close();
$conn->close();
