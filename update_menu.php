<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// POST verilerini al
$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$items = isset($_POST['items']) ? $_POST['items'] : null;
$calories = isset($_POST['calories']) ? intval($_POST['calories']) : 0;
$contains_gluten = isset($_POST['contains_gluten']) ? intval($_POST['contains_gluten']) : 0;
$is_vegetarian = isset($_POST['is_vegetarian']) ? intval($_POST['is_vegetarian']) : 0;

// Eksik veri kontrolü
if ($id === null || $date === null || $items === null) {
    echo json_encode(array("success" => false, "message" => "Eksik veya geçersiz parametreler."));
    $conn->close();
    exit;
}

// Güncelleme sorgusu
$sql = "UPDATE menu 
        SET date = ?, items = ?, calories = ?, contains_gluten = ?, is_vegetarian = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ssiiii", $date, $items, $calories, $contains_gluten, $is_vegetarian, $id);

    if ($stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Menü başarıyla güncellendi."));
    } else {
        echo json_encode(array("success" => false, "message" => "Menü güncellenirken bir hata oluştu: " . $stmt->error));
    }
    $stmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "Sorgu hazırlanırken bir hata oluştu: " . $conn->error));
}

$conn->close();
?>
