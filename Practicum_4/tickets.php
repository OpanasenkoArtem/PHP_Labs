<?php
// Функції роботи з таблицею tickets (варіант 19). Усі запити з параметрами - підготовлені.

const PRIORITIES = ['low' => 'Низький', 'medium' => 'Середній', 'high' => 'Високий'];
const STATUSES   = ['open' => 'Відкритий', 'in_progress' => 'В роботі', 'closed' => 'Закритий'];

function listAll(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM tickets ORDER BY id DESC')->fetchAll();
}

function openTickets(PDO $pdo): array
{
    return $pdo->query("SELECT * FROM tickets WHERE status = 'open'")->fetchAll();
}

function findByPriority(PDO $pdo, string $priority): array
{
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE priority = :priority');
    $stmt->execute([':priority' => $priority]);
    return $stmt->fetchAll();
}

function findById(PDO $pdo, int $id): array|false
{
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function addTicket(PDO $pdo, string $subject, string $priority, string $status = 'open'): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO tickets (subject, priority, status) VALUES (:subject, :priority, :status)'
    );
    $stmt->execute([':subject' => $subject, ':priority' => $priority, ':status' => $status]);
    return (int) $pdo->lastInsertId();
}

function updateTicket(PDO $pdo, int $id, string $subject, string $priority, string $status): int
{
    $stmt = $pdo->prepare(
        'UPDATE tickets SET subject = :subject, priority = :priority, status = :status WHERE id = :id'
    );
    $stmt->execute([':subject' => $subject, ':priority' => $priority, ':status' => $status, ':id' => $id]);
    return $stmt->rowCount();
}

function updateStatus(PDO $pdo, int $id, string $status): int
{
    $stmt = $pdo->prepare('UPDATE tickets SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $id]);
    return $stmt->rowCount();
}

function deleteTicket(PDO $pdo, int $id): int
{
    $stmt = $pdo->prepare('DELETE FROM tickets WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount();
}

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
