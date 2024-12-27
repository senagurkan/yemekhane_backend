<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


$admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : null;
$feedback_id = isset($_POST['feedback_id']) ? $_POST['feedback_id'] : null;

if (!$admin_id || !$feedback_id) {
    echo json_encode(array("success" => false, "message" => "Eksik parametreler."));
    $conn->close();
    exit();
}

$sql = "DELETE FROM Saved_Feedbacks WHERE admin_id = ? AND feedback_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $admin_id, $feedback_id);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Kaydedilen geri bildirim başarıyla silindi."));
} else {
    echo json_encode(array("success" => false, "message" => "Kaydı geri alma sırasında bir hata oluştu."));
}

$conn->close();
?>
