<?php
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı
require_once 'config.php';


// Gelen POST verilerini al
$date = isset($_POST['date']) ? $_POST['date'] : null;
$items = isset($_POST['items']) ? $_POST['items'] : null;
$calories = isset($_POST['calories']) && !empty($_POST['calories']) ? $_POST['calories'] : 0;
$contains_gluten = isset($_POST['contains_gluten']) ? $_POST['contains_gluten'] : 0;
$is_vegetarian = isset($_POST['is_vegetarian']) ? $_POST['is_vegetarian'] : 0;
$created_by = isset($_POST['created_by']) ? $_POST['created_by'] : 1;

// Eksik veri kontrolü
if (!$date || !$items) {
    die(json_encode(array("error" => "Tarih ve menü içeriği zorunludur.")));
}

// Aynı tarih için menü kontrolü
$sql_check = "SELECT * FROM menu WHERE date = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $date);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    die(json_encode(array("error" => "Bu tarih için zaten bir menü mevcut.")));
}

// Menü ekleme sorgusu
$sql = "INSERT INTO menu (date, items, calories, contains_gluten, is_vegetarian, created_by) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiiii", $date, $items, $calories, $contains_gluten, $is_vegetarian, $created_by);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Menü başarıyla eklendi."));
} else {
    echo json_encode(array(
        "error" => "Menü eklenirken bir hata oluştu.",
        "details" => $stmt->error
    ));
}

$stmt->close();
$conn->close();
?>
