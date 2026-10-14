<?php
require_once 'classes/Ticket.php';
require_once 'classes/UrgentTicket.php';
require_once 'classes/HelpDesk.php';
require_once 'functions/functions.php';

$manager = new HelpDesk();

$ticket1 = new Ticket("Не працює мишка", "Низький", "Closed");
$ticket2 = new Ticket("Помилка входу в систему", "Середній", "Open");
$urgentTicket1 = new UrgentTicket("Впав сервер бази даних", "Критичний", "Open", 2);
$urgentTicket2 = new UrgentTicket("Збій платіжного шлюзу", "Високий", "Open", 4);

$manager->addTicket($ticket1);
$manager->addTicket($ticket2);
$manager->addTicket($urgentTicket1);
$manager->addTicket($urgentTicket2);

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>HelpDesk</title>
</head>
<body>
    <h1>Всі тікети в системі</h1>
    <ul>
        <?php foreach ($manager->getAllTickets() as $ticket): ?>
            <li>
                <?= $ticket->getInfo() ?> 
                (Оновлений статус: <?= formatStatus($ticket->getStatus()) ?>)
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Лише відкриті тікети</h2>
    <ul>
        <?php foreach ($manager->openTickets() as $ticket): ?>
            <li><?= $ticket->getInfo() ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Пошук за пріоритетом 'Критичний'</h2>
    <ul>
        <?php foreach ($manager->findByPriority('Критичний') as $ticket): ?>
            <li>
                <?= $ticket->getInfo() ?>
                <?php 
                if ($ticket instanceof UrgentTicket) {
                    $isOverdue = isOverdue($ticket->getSlaHours(), 3) ? "Так" : "Ні";
                    echo " | <b>Прострочено (перевірка 3 год):</b> $isOverdue";
                }
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>