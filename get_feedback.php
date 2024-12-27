<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Parametreleri al
$category = isset($_GET['category']) ? $_GET['category'] : null;
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
$month = isset($_GET['month']) ? $_GET['month'] : null;
$year = isset($_GET['year']) ? $_GET['year'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : null; // Kullanıcı ID'si alınır

// Sorguyu oluştur
$sql = "SELECT 
            f.feedback_id, 
            f.user_id, 
            f.category, 
            f.content, 
            f.image_url, 
            f.created_at, 
            (SELECT COUNT(*) FROM Feedback_Likes WHERE feedback_id = f.feedback_id) AS like_count,
            (SELECT COUNT(*) FROM Feedback_Replies WHERE feedback_id = f.feedback_id) AS reply_count,
            u.nickname AS user_nickname, 
            u.color AS user_color,
            (SELECT COUNT(*) FROM Feedback_Likes WHERE feedback_id = f.feedback_id AND user_id = '$userId') > 0 AS user_liked,
            (SELECT COUNT(*) FROM Saved_Feedbacks WHERE feedback_id = f.feedback_id AND admin_id = '$userId') > 0 AS is_saved
        FROM Feedback f
        LEFT JOIN Users u ON f.user_id = u.id
        WHERE 1=1";

// Kategoriye göre filtreleme
if ($category && $category !== 'all') {
    $sql .= " AND f.category = '$category'";
}

// Tarih aralığına göre filtreleme
if ($startDate && $endDate) {
    $sql .= " AND f.created_at BETWEEN '$startDate' AND '$endDate'";
}

// Ay ve yıla göre filtreleme
if ($month && $year) {
    $sql .= " AND MONTH(f.created_at) = '$month' AND YEAR(f.created_at) = '$year'";
}

// Sıralama
if ($sort === 'most_liked') {
    $sql .= " ORDER BY like_count DESC";
} else { // Default: newest
    $sql .= " ORDER BY f.created_at DESC";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $feedbacks = array();
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = array(
            "feedback_id" => (int)$row["feedback_id"], // Integer türüne dönüştür
            "user_id" => (int)$row["user_id"],
            "category" => $row["category"],
            "content" => $row["content"],
            "image_url" => $row["image_url"],
            "created_at" => $row["created_at"],
            "like_count" => (int)$row["like_count"],
            "reply_count" => (int)$row["reply_count"],
            "user_nickname" => $row["user_nickname"],
            "user_color" => $row["user_color"],
            "user_liked" => (bool)$row["user_liked"], // Boolean türüne dönüştür
            "is_saved" => (bool)$row["is_saved"], // `is_saved` alanını ekliyoruz
        );
    }
    echo json_encode(array("success" => true, "feedbacks" => $feedbacks));
} else {
    echo json_encode(array("success" => false, "message" => "Geri bildirim bulunamadı."));
}

$conn->close();
?>
