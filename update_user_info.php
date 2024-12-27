<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';

// Gelen veriyi al
$data = json_decode(file_get_contents("php://input"), true);

$user_id = isset($data['user_id']) ? intval($data['user_id']) : null;
$username = isset($data['username']) ? $data['username'] : null;
$icon_color = isset($data['icon_color']) ? $data['icon_color'] : null;

if (!$user_id || !$username || !$icon_color) {
    echo json_encode(array("success" => false, "message" => "Eksik parametreler."));
    exit();
}

// Kullanıcı bilgilerini güncelle
$sql = "UPDATE users SET username = ?, icon_color = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $username, $icon_color, $user_id);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Bilgiler başarıyla güncellendi."));
} else {
    echo json_encode(array("success" => false, "message" => "Bilgiler güncellenemedi."));
}

$stmt->close();
$conn->close();
?>
