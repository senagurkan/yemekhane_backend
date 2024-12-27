<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';

// Gelen veriler
$menu_id = $_GET['menu_id'];
$user_id = $_GET['user_id'];

// Kullanıcının puanını al
$sql = "SELECT rating FROM ratings WHERE menu_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $menu_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $rating = $result->fetch_assoc()['rating'];
    echo json_encode(array("success" => true, "rating" => $rating));
} else {
    echo json_encode(array("success" => true, "rating" => null));
}

$stmt->close();
$conn->close();
?>
