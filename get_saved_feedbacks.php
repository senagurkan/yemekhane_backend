<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


$admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : null;

if (!$admin_id) {
    echo json_encode(array("success" => false, "message" => "Admin ID eksik."));
    $conn->close();
    exit();
}

$sql = "SELECT 
            f.feedback_id, 
            f.category, 
            f.content, 
            f.image_url, 
            f.created_at, 
            u.nickname AS user_nickname, 
            u.color AS user_color,
            (SELECT COUNT(*) FROM Feedback_Likes WHERE feedback_id = f.feedback_id) AS like_count
        FROM Saved_Feedbacks sf
        JOIN Feedback f ON sf.feedback_id = f.feedback_id
        LEFT JOIN Users u ON f.user_id = u.id
        WHERE sf.admin_id = ?
        ORDER BY sf.saved_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $feedbacks = array();
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = array(
            "feedback_id" => (int)$row["feedback_id"],
            "category" => $row["category"],
            "content" => $row["content"],
            "image_url" => $row["image_url"],
            "created_at" => $row["created_at"],
            "user_nickname" => $row["user_nickname"], // Kullanıcı adı
            "user_color" => $row["user_color"], // Kullanıcı rengi
            "like_count" => (int)$row["like_count"], // Beğeni sayısı
        );
    }
    echo json_encode(array("success" => true, "feedbacks" => $feedbacks));
} else {
    echo json_encode(array("success" => false, "message" => "Kaydedilen geri bildirim bulunamadı."));
}

$conn->close();
?>
