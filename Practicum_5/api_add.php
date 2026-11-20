<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

// Приймаємо JSON-тіло; якщо його немає - беремо звичайну форму ($_POST)
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$subject  = trim($input['subject'] ?? '');
$priority = $input['priority'] ?? 'medium';
$status   = $input['status'] ?? 'open';

$priorities = ['low', 'medium', 'high'];
$statuses   = ['open', 'in_progress', 'closed'];

if ($subject === '' || !in_array($priority, $priorities, true) || !in_array($status, $statuses, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Некоректні дані: перевірте тему, пріоритет і статус']);
    exit;
}

$stmt = $pdo->prepare('INSERT INTO tickets (subject, priority, status) VALUES (:s, :p, :st)');
$stmt->execute(['s' => $subject, 'p' => $priority, 'st' => $status]);

http_response_code(201);
echo json_encode([
    'id'       => (int)$pdo->lastInsertId(),
    'subject'  => $subject,
    'priority' => $priority,
    'status'   => $status,
]);
