<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
$ids = $_GET['ids'] ?? '';
if ($ids === '') {
    $rows = $pdo->query("SELECT p.id,p.name,p.price,p.stock,p.image,p.description,c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.is_active=1 ORDER BY p.created_at DESC")->fetchAll();
} else {
    $list = array_values(array_unique(array_filter(array_map('intval', explode(',', $ids)), fn($n) => $n > 0)));
    if (!$list) { echo json_encode([]); exit; }
    $marks = implode(',', array_fill(0, count($list), '?'));
    $stmt = $pdo->prepare("SELECT p.id,p.name,p.price,p.stock,p.image,p.description,c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.is_active=1 AND p.id IN ($marks)");
    $stmt->execute($list); $rows = $stmt->fetchAll();
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
