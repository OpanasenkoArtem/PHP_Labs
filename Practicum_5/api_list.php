<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

// Пошук за статусом: ?q=open
$q = $_GET['q'] ?? '';

$stmt = $pdo->prepare(
    'SELECT id, subject, priority, status FROM tickets WHERE status LIKE :q ORDER BY id'
);
$stmt->execute(['q' => '%' . $q . '%']);

echo json_encode($stmt->fetchAll());
