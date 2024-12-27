<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// Kullanıcı ID kontrolü
if (!isset($_GET['user_id'])) {
    die(json_encode(array("success" => false, "message" => "Kullanıcı ID'si eksik.")));
}

$user_id = intval($_GET['user_id']);

// Yorumları ve menü detaylarını sorgulama
$sql = "SELECT 
            c.id AS comment_id, 
            c.comment, 
            c.created_at, 
            m.date AS menu_date, 
            m.items AS menu_items
        FROM comments c
        JOIN menu m ON c.menu_id = m.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(array("success" => false, "message" => "Sorgu hazırlama hatası: " . $conn->error)));
}

// Kullanıcı ID'sini bağla ve sorguyu çalıştır
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Yorumları JSON formatında döndür
$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

// JSON yanıtı
echo json_encode(array("success" => true, "comments" => $comments));

// Sorguyu kapat ve bağlantıyı sonlandır
$stmt->close();
$conn->close();
?>
