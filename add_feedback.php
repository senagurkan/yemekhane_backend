<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// POST verilerini al
$user_id = $_POST['user_id'] ?? null;
$category = $_POST['category'] ?? null;
$content = $_POST['content'] ?? null;
$image = $_FILES['image'] ?? null;

// Zorunlu alanların kontrolü
if (!$user_id || !$category || !$content) {
    echo json_encode(array("success" => false, "message" => "Zorunlu alanlar eksik."));
    exit;
}

// Görsel yükleme işlemi
$image_url = null;
if ($image && $image['error'] == UPLOAD_ERR_OK) {
    $uploads_dir = __DIR__ . "/uploads";
    $image_name = uniqid() . "_" . basename($image['name']);
    $image_path = $uploads_dir . "/" . $image_name;

    // Klasör mevcut değilse oluştur
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    // Görseli taşı
    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        $image_url = "uploads/" . $image_name;
    } else {
        echo json_encode(array("success" => false, "message" => "Görsel yüklenemedi."));
        exit;
    }
}

// Veriyi veritabanına ekle
$sql = "INSERT INTO Feedback (user_id, category, content, image_url) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $user_id, $category, $content, $image_url);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "İstek/Şikayet başarıyla eklendi."));
} else {
    echo json_encode(array("success" => false, "message" => "Veritabanına ekleme sırasında hata: " . $stmt->error));
}

// Bağlantıyı kapat
$stmt->close();
$conn->close();
?>
