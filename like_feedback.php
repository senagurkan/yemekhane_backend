<?php
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "yemekhane_uygulamasi");

require_once 'config.php';


// POST parametrelerini al
$feedback_id = isset($_POST['feedback_id']) ? (int) $_POST['feedback_id'] : null;
$user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : null;

if (!$feedback_id || !$user_id) {
    die(json_encode(array("success" => false, "message" => "Eksik parametreler.")));
}

// Kullanıcı daha önce beğenmiş mi kontrol et
$checkLikeQuery = "SELECT * FROM Feedback_Likes WHERE feedback_id = $feedback_id AND user_id = $user_id";
$checkLikeResult = $conn->query($checkLikeQuery);

if ($checkLikeResult->num_rows > 0) {
    // Eğer beğenilmişse, beğeniyi kaldır
    $deleteLikeQuery = "DELETE FROM Feedback_Likes WHERE feedback_id = $feedback_id AND user_id = $user_id";
    if ($conn->query($deleteLikeQuery)) {
        echo json_encode(array("success" => true, "message" => "Beğeni kaldırıldı."));
    } else {
        echo json_encode(array("success" => false, "message" => "Beğeni kaldırılırken hata oluştu."));
    }
} else {
    // Eğer beğenilmemişse, beğeni ekle
    $insertLikeQuery = "INSERT INTO Feedback_Likes (feedback_id, user_id) VALUES ($feedback_id, $user_id)";
    if ($conn->query($insertLikeQuery)) {
        echo json_encode(array("success" => true, "message" => "Beğeni eklendi."));
    } else {
        echo json_encode(array("success" => false, "message" => "Beğeni eklenirken hata oluştu."));
    }
}

$conn->close();
?>
