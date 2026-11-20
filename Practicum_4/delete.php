<?php
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/tickets.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0) {
        deleteTicket($pdo, $id);
    }
}
header('Location: index.php');
exit;
