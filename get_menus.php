<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';


// Menüleri çekme sorgusu
$sql = "SELECT id, date, items, calories, contains_gluten, is_vegetarian FROM menu ORDER BY date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $menus = array();
    while ($row = $result->fetch_assoc()) {
        $menus[] = array(
            "id" => $row["id"],
            "date" => $row["date"],
            "items" => $row["items"],
            "calories" => $row["calories"],
            "contains_gluten" => $row["contains_gluten"] == 1 ? true : false,
            "is_vegetarian" => $row["is_vegetarian"] == 1 ? true : false,
        );
    }
    echo json_encode(array("success" => true, "menus" => $menus));
} else {
    echo json_encode(array("success" => false, "message" => "Menü bulunamadı."));
}

$conn->close();
?>
