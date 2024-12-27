<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// Gelen verileri al
$menu_id = $_POST['menu_id'];
$user_id = $_POST['user_id'];
$rating = $_POST['rating'];

// Kullanıcının zaten bu menüye puan verip vermediğini kontrol et
$check_sql = "SELECT * FROM ratings WHERE menu_id = ? AND user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $menu_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Zaten puan verilmişse hata döndür
    echo json_encode(array("success" => false, "message" => "Bu menüye zaten puan verdiniz."));
} else {
    // Yeni puan ekle
    $insert_sql = "INSERT INTO ratings (menu_id, user_id, rating, created_at) VALUES (?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iii", $menu_id, $user_id, $rating);

    if ($insert_stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Puan başarıyla eklendi."));
    } else {
        echo json_encode(array("success" => false, "message" => $insert_stmt->error));
    }

    $insert_stmt->close();
}

$check_stmt->close();
$conn->close();
?>
