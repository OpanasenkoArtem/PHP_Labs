<?php
// Швидка зміна статусу (updateStatus) - кнопка «Закрити» в таблиці
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/tickets.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int) ($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($id > 0 && isset(STATUSES[$status])) {
        updateStatus($pdo, $id, $status);
    }
}
header('Location: index.php');
exit;
