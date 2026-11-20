<?php
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/tickets.php';

$priority = $_GET['priority'] ?? '';
$filter   = $_GET['filter'] ?? '';

if ($filter === 'open') {
    $tickets = openTickets($pdo);
} elseif (isset(PRIORITIES[$priority])) {
    $tickets = findByPriority($pdo, $priority);
} else {
    $tickets = listAll($pdo);
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Helpdesk - тікети</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 2rem auto; }
        table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
        th, td { border: 1px solid #ccc; padding: .4rem .6rem; text-align: left; }
        th { background: #f0f0f0; }
        .error { color: #b00; }
        form.inline { display: inline; }
    </style>
</head>
<body>
<h1>Система тікетів</h1>

<?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>

<p>
    Фільтр:
    <a href="index.php">Усі</a> |
    <a href="index.php?filter=open">Відкриті</a> |
    <?php foreach (PRIORITIES as $key => $label): ?>
        <a href="index.php?priority=<?= e($key) ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
</p>

<table>
    <tr><th>ID</th><th>Тема</th><th>Пріоритет</th><th>Статус</th><th>Дії</th></tr>
    <?php foreach ($tickets as $t): ?>
        <tr>
            <td><?= (int) $t['id'] ?></td>
            <td><?= e($t['subject']) ?></td>
            <td><?= e(PRIORITIES[$t['priority']] ?? $t['priority']) ?></td>
            <td><?= e(STATUSES[$t['status']] ?? $t['status']) ?></td>
            <td>
                <a href="edit.php?id=<?= (int) $t['id'] ?>">Редагувати</a>
                <?php if ($t['status'] !== 'closed'): ?>
                    <form class="inline" method="post" action="status.php">
                        <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                        <input type="hidden" name="status" value="closed">
                        <button type="submit">Закрити</button>
                    </form>
                <?php endif; ?>
                <form class="inline" method="post" action="delete.php"
                      onsubmit="return confirm('Видалити тікет #<?= (int) $t['id'] ?>?');">
                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                    <button type="submit">Видалити</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$tickets): ?>
        <tr><td colspan="5">Тікетів немає.</td></tr>
    <?php endif; ?>
</table>

<h2>Новий тікет</h2>
<form method="post" action="add.php">
    <p><label>Тема: <input type="text" name="subject" maxlength="255" required></label></p>
    <p><label>Пріоритет:
        <select name="priority">
            <?php foreach (PRIORITIES as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= $key === 'medium' ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select></label></p>
    <p><label>Статус:
        <select name="status">
            <?php foreach (STATUSES as $key => $label): ?>
                <option value="<?= e($key) ?>"><?= e($label) ?></option>
            <?php endforeach; ?>
        </select></label></p>
    <button type="submit">Додати</button>
</form>
</body>
</html>
