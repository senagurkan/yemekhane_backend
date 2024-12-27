<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Parametreleri al
$admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : null;
$feedback_id = isset($_POST['feedback_id']) ? $_POST['feedback_id'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : 'save'; // Varsayılan olarak kaydetme işlemi

if (!$admin_id || !$feedback_id) {
    echo json_encode(array("success" => false, "message" => "Eksik parametreler."));
    $conn->close();
    exit();
}

if ($action === 'save') {
    // Kaydı kontrol et
    $check_sql = "SELECT * FROM Saved_Feedbacks WHERE admin_id = ? AND feedback_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $admin_id, $feedback_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "Bu yorum zaten kaydedilmiş."));
    } else {
        // Kaydetme işlemi
        $sql = "INSERT INTO Saved_Feedbacks (admin_id, feedback_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $admin_id, $feedback_id);

        if ($stmt->execute()) {
            echo json_encode(array("success" => true, "message" => "Yorum başarıyla kaydedildi."));
        } else {
            echo json_encode(array("success" => false, "message" => "Kaydetme işlemi sırasında bir hata oluştu."));
        }
    }
} elseif ($action === 'unsave') {
    // Geri alma işlemi
    $sql = "DELETE FROM Saved_Feedbacks WHERE admin_id = ? AND feedback_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $admin_id, $feedback_id);

    if ($stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Yorum başarıyla geri alındı."));
    } else {
        echo json_encode(array("success" => false, "message" => "Geri alma işlemi sırasında bir hata oluştu."));
    }
}

$conn->close();
?>
