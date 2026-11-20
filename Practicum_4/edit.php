<?php
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/tickets.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = (int) ($_POST['id'] ?? 0);
    $subject  = trim($_POST['subject'] ?? '');
    $priority = $_POST['priority'] ?? '';
    $status   = $_POST['status'] ?? '';

    if ($id <= 0 || $subject === '' || !isset(PRIORITIES[$priority]) || !isset(STATUSES[$status])) {
        header('Location: index.php?error=' . urlencode('Некоректні дані форми'));
        exit;
    }
    updateTicket($pdo, $id, $subject, $priority, $status);
    header('Location: index.php');
    exit;
}

$ticket = findById($pdo, (int) ($_GET['id'] ?? 0));
if (!$ticket) {
    header('Location: index.php?error=' . urlencode('Тікет не знайдено'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагування тікета</title>
</head>
<body>
<h1>Редагування тікета #<?= (int) $ticket['id'] ?></h1>
<form method="post" action="edit.php">
    <input type="hidden" name="id" value="<?= (int) $ticket['id'] ?>">
    <p><label>Тема:
        <input type="text" name="subject" maxlength="255" required value="<?= e($ticket['subject']) ?>"></label></p>
    <p><label>Пріоритет:
        <select name="priority">
            <?php foreach (PRIORITIES as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= $key === $ticket['priority'] ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select></label></p>
    <p><label>Статус:
        <select name="status">
            <?php foreach (STATUSES as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= $key === $ticket['status'] ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select></label></p>
    <button type="submit">Зберегти</button>
    <a href="index.php">Скасувати</a>
</form>
</body>
</html>
