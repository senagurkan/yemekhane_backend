<?php
// Hata raporlama için eklendi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// GET parametresi kontrolü
if (!isset($_GET['user_id'])) {
    die(json_encode(array("success" => false, "message" => "user_id parametresi eksik.")));
}

$user_id = intval($_GET['user_id']);

// Kullanıcı bilgilerini sorgula
$sql = "SELECT id, username, nickname, color FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(array("success" => false, "message" => "Sorgu hazırlama hatası: " . $conn->error)));
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(array("success" => true, "user" => $user));
} else {
    echo json_encode(array("success" => false, "message" => "Kullanıcı bulunamadı."));
}

$stmt->close();
$conn->close();
?>
