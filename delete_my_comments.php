<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// Gelen parametreleri kontrol et
if (!isset($_POST['comment_id'])) {
    die(json_encode(array("success" => false, "message" => "Yorum ID'si eksik.")));
}

$comment_id = intval($_POST['comment_id']);

// Yorum silme sorgusu
$sql = "DELETE FROM comments WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(array("success" => false, "message" => "Sorgu hazırlama hatası: " . $conn->error)));
}

// Yorum ID'sini bağla ve sorguyu çalıştır
$stmt->bind_param("i", $comment_id);
if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Yorum başarıyla silindi."));
} else {
    echo json_encode(array("success" => false, "message" => "Yorum silinirken bir hata oluştu."));
}

// Sorguyu kapat ve bağlantıyı sonlandır
$stmt->close();
$conn->close();
?>
