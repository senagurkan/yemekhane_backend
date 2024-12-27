<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';
// Gelen veriler
$menu_id = $_POST['menu_id'];
$user_id = $_POST['user_id'];
$comment = $_POST['comment'];
$rating = $_POST['rating'] ?? null; // Puan isteğe bağlı olabilir

if (empty($menu_id) || empty($user_id) || empty($comment)) {
    echo json_encode(array("success" => false, "message" => "Tüm alanlar gereklidir."));
    exit;
}

$sql = "INSERT INTO comments (menu_id, user_id, comment, rating) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisi", $menu_id, $user_id, $comment, $rating);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Yorum başarıyla eklendi."));
} else {
    echo json_encode(array("success" => false, "message" => $stmt->error));
}

$stmt->close();
$conn->close();
?>
