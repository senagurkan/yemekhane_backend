<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


$menu_id = $_GET['menu_id'];

$sql = "SELECT c.id, c.comment, c.rating, c.created_at, u.nickname, u.color 
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.menu_id = ?
        ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = array();
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode(array("success" => true, "comments" => $comments));

$stmt->close();
$conn->close();
?>
