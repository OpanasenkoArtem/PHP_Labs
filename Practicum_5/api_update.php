<?php
// Зміна статусу тікета (для випадного списку в рядку таблиці)
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$id     = (int)($input['id'] ?? 0);
$status = $input['status'] ?? '';

if ($id <= 0 || !in_array($status, ['open', 'in_progress', 'closed'], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Некоректні дані']);
    exit;
}

$stmt = $pdo->prepare('UPDATE tickets SET status = :st WHERE id = :id');
$stmt->execute(['st' => $status, 'id' => $id]);

echo json_encode(['id' => $id, 'status' => $status]);
