<?php
// JSON yanıtı için gerekli başlık
header('Content-Type: application/json');

require_once 'config.php';


// Gelen ID'yi al
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID parametresi eksik']);
    exit;
}
$id = intval($_GET['id']);

// Menü detaylarını veritabanından al
$query = "SELECT * FROM menu WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

$menu = $stmt->fetch(PDO::FETCH_ASSOC);

// Menü bulunduysa JSON yanıtı döndür
if ($menu) {
    echo json_encode([
        'success' => true,
        'menu' => [
            'items' => $menu['items'],
            'calories' => $menu['calories'],
            'contains_gluten' => $menu['contains_gluten'],
            'is_vegetarian' => $menu['is_vegetarian']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Menü bulunamadı']);
}
