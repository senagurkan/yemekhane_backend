<?php
header("Content-Type: application/json; charset=UTF-8");

// Debug için ekleyin
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// Gelen tarihi kontrol edelim
$start_date = $_GET['start_date'];
error_log("Requested start_date: " . $start_date);

// SQL sorgusu için bitiş tarihini hesaplayalım
$end_date = date('Y-m-d', strtotime($start_date . ' +6 days'));
error_log("End date: " . $end_date);

// SQL sorgusunu güncelleyelim
$sql = "SELECT id, date, items, calories, is_vegetarian, contains_gluten 
        FROM menu 
        WHERE date BETWEEN ? AND ? 
        ORDER BY date ASC";

// Sorguyu loglayalım
error_log("SQL Query: " . $sql);

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("SQL Error: " . $conn->error);
    die(json_encode(["success" => false, "message" => "Query preparation failed: " . $conn->error]));
}

$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();

$result = $stmt->get_result();
$menus = [];

// Menü sonuçlarını döngüyle ekleyelim
while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

// Sonuçları loglayalım
error_log("Number of menus found: " . count($menus));

// JSON yanıtını gönderelim
echo json_encode([
    "success" => true,
    "menus" => $menus,
    "debug" => [
        "start_date" => $start_date,
        "end_date" => $end_date,
        "menu_count" => count($menus)
    ]
]);

$stmt->close();
$conn->close();
?>
