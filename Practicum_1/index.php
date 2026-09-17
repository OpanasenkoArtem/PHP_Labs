<?php
$tickets = [
    [
        'subject' => 'Збій сервера бази даних після оновлення', 'priority' => 'urgent', 'status' => 'open'
    ],
    [
        'subject' => 'Помилка авторизації через Google', 'priority' => 'high', 'status' => 'open'
    ],
    [
        'subject' => 'Некоректний підрахунок знижки в кошику', 'priority' => 'medium', 'status' => 'closed'
    ],
    [
        'subject' => 'Запит на зміну корпоративної пошти працівника', 'priority' => 'low', 'status' => 'closed'
    ],
    [
        'subject' => 'Помилка в тексті на сайті', 'priority' => 'low', 'status' => 'open'
    ]
];

function formatTicket(array $ticket): string {
    return $ticket['subject'];
}

function Priority(string $priority): string {
    if ($priority === 'urgent') {
        return 'ТЕРМІНОВИЙ';
    }
    return $priority;
}

$open = 0;
$closed = 0;

foreach ($tickets as $ticket) {
    if ($ticket['status'] === 'open') {
        $open++;
    } elseif ($ticket['status'] === 'closed') {
        $closed++;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Helpdesk</title>
</head>
<body>

    <h1>Helpdesk</h1>

    <h2>Список звернень:</h2>
    <ul>
        <?php foreach ($tickets as $ticket): ?>
            <li>
                <strong>Тема:</strong> <?= formatTicket($ticket) ?> | 
                <strong>Пріоритет:</strong> <?= Priority($ticket['priority']) ?> | 
                <strong>Статус:</strong> <?= $ticket['status'] ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Кількість тікетів:</h2>
    <p>
    Відкритих тікетів: <?= $open ?><br>
    Закритих тікетів: <?= $closed ?>
    </p>

</body>
</html>