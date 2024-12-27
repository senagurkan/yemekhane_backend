<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Gelen veriyi al
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

if (!$user_id) {
    echo json_encode(array("success" => false, "message" => "Kullanıcı ID eksik."));
    exit();
}

// Kullanıcının yorumlarını çek
$sql = "SELECT comments.comment, menu.date AS menu_date
        FROM comments
        JOIN menu ON comments.menu_id = menu.id
        WHERE comments.user_id = ?
        ORDER BY comments.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = array();
while ($row = $result->fetch_assoc()) {
    $comments[] = array(
        "comment" => $row['comment'],
        "menu_date" => $row['menu_date'],
    );
}

echo json_encode(array("success" => true, "comments" => $comments));

$stmt->close();
$conn->close();
?>
