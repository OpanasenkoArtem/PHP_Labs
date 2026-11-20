<?php
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/tickets.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$subject  = trim($_POST['subject'] ?? '');
$priority = $_POST['priority'] ?? '';
$status   = $_POST['status'] ?? 'open';

if ($subject === '' || !isset(PRIORITIES[$priority]) || !isset(STATUSES[$status])) {
    header('Location: index.php?error=' . urlencode('Некоректні дані форми'));
    exit;
}

addTicket($pdo, $subject, $priority, $status);
header('Location: index.php');
exit;
