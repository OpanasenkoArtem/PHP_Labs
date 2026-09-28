<?php
$subject = trim($_POST['subject'] ?? '');
$description = trim($_POST['description'] ?? '');
$priority = $_POST['priority'] ?? '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($subject === '') {
        $errors[] = "Тема обов'язкова";
    }
    if (strlen($description) < 15) {
        $errors[] = "Опис має містити не менше 15 символів";
    }
    if (!in_array($priority, ['low', 'medium', 'high', 'urgent'])) {
        $errors[] = "Оберіть коректний пріоритет";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Тікет підтримки</title>
</head>
<body>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)): ?>
    <?php
    $priorities = [
        'low'    => 'Низький',
        'medium' => 'Середній',
        'high'   => 'Високий',
        'urgent' => 'Терміновий'
    ];
    ?>
    <!-- 1. Результат успішної обробки -->
    <h3>Тікет прийнято!</h3>
    <p>Тема: <?= htmlspecialchars($subject) ?></p>
    <p>Пріоритет: <?= htmlspecialchars($priorities[$priority] ?? $priority) ?></p>
    <p>Опис: <?= nl2br(htmlspecialchars($description)) ?></p>

    <script>
        localStorage.removeItem('draft_desc');
    </script>
    <a href="form.php">Назад</a>

<?php else: ?>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form id="ticketForm" method="post" action="form.php">
        <p>
            Тема:<br>
            <input type="text" name="subject" value="<?= htmlspecialchars($subject) ?>" required>
        </p>

        
<label>Пріоритет:
    <select name="priority" required>
        <option value="">-- виберіть --</option>
        <?php foreach (['low'=>'Низький', 'medium'=>'Середній', 'high'=>'Високий', 'urgent'=>'Терміновий'] as $k => $v): ?>
            <option value="<?= $k ?>" <?= $priority === $k ? 'selected' : '' ?>><?= $v ?></option>
        <?php endforeach; ?>
    </select>
</label>

        <p>
            Опис (від 15 символів):<br>
            <textarea id="desc" name="description" rows="4" cols="30" required minlength="15"><?= htmlspecialchars($description) ?></textarea>
        </p>

        <button type="submit">Надіслати</button>
    </form>

    <script>
        const desc = document.getElementById('desc');

        // Відновлення чернетки, якщо поле пусте
        if (!desc.value) {
            desc.value = localStorage.getItem('draft_desc') || '';
        }

        desc.addEventListener('input', () => {
            localStorage.setItem('draft_desc', desc.value);
        });

        document.getElementById('ticketForm').addEventListener('submit', (e) => {
            if (desc.value.trim().length < 15) {
                e.preventDefault();
                alert('Менше 15 символів');
            }
        });
    </script>
<?php endif; ?>

</body>
</html>