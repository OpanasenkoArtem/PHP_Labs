<?php
/*
 * API служби підтримки (варіант 19) — контракт
 *
 * GET  /api.php?resource=tickets            -> 200, {"success":true,"data":[...]}
 * GET  /api.php?resource=tickets&id=N       -> 200, один тікет | 400 (id некоректний) | 404 (немає)
 * POST /api.php?resource=tickets            -> 201, створений тікет
 *        поля: subject (обов'язкове), priority (low|medium|high, обов'язкове)
 *        400, якщо поле відсутнє/некоректне
 * POST /api.php?resource=tickets&action=close&id=N
 *                                           -> 200, тікет зі status = "closed" | 400 | 404
 * Інші методи для tickets -> 405; невідомий ресурс -> 404; невідома action -> 400
 * Помилка: {"success":false,"error":"..."}
 */

header('Content-Type: application/json; charset=utf-8');

// ---------- допоміжні функції ----------
function respond(int $code, array $payload): void
{
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function fail(int $code, string $message): void
{
    respond($code, ['success' => false, 'error' => $message]);
}

// Дані POST: JSON-тіло або звичайна форма
function readInput(): array
{
    $raw = file_get_contents('php://input');
    if ($raw !== '' && $raw !== false) {
        $json = json_decode($raw, true);
        if (is_array($json)) {
            return $json;
        }
    }
    return $_POST;
}

// Повертає int > 0 або null
function parseId($value): ?int
{
    if ($value === null || !ctype_digit((string) $value) || (int) $value < 1) {
        return null;
    }
    return (int) $value;
}

function findTicket(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT id, subject, priority, status FROM tickets WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}

// ---------- обробники ----------
function listTickets(PDO $pdo): void
{
    $rows = $pdo->query('SELECT id, subject, priority, status FROM tickets ORDER BY id')->fetchAll();
    respond(200, ['success' => true, 'data' => $rows]);
}

function getTicket(PDO $pdo, $rawId): void
{
    $id = parseId($rawId);
    if ($id === null) {
        fail(400, 'Параметр id має бути додатним цілим числом');
    }
    $ticket = findTicket($pdo, $id);
    if ($ticket === null) {
        fail(404, "Тікет з id=$id не знайдено");
    }
    respond(200, ['success' => true, 'data' => $ticket]);
}

function createTicket(PDO $pdo, array $input): void
{
    $subject  = trim((string) ($input['subject'] ?? ''));
    $priority = (string) ($input['priority'] ?? '');

    if ($subject === '') {
        fail(400, 'Поле subject обов\'язкове');
    }
    if (mb_strlen($subject) > 255) {
        fail(400, 'Поле subject задовге (максимум 255 символів)');
    }
    if ($priority === '') {
        fail(400, 'Поле priority обов\'язкове');
    }
    if (!in_array($priority, ['low', 'medium', 'high'], true)) {
        fail(400, 'Поле priority має бути одним зі значень: low, medium, high');
    }

    $stmt = $pdo->prepare('INSERT INTO tickets (subject, priority, status) VALUES (?, ?, \'open\')');
    $stmt->execute([$subject, $priority]);

    respond(201, ['success' => true, 'data' => findTicket($pdo, (int) $pdo->lastInsertId())]);
}

function closeTicket(PDO $pdo, $rawId): void
{
    $id = parseId($rawId);
    if ($id === null) {
        fail(400, 'Для action=close потрібен параметр id (додатне ціле число)');
    }
    if (findTicket($pdo, $id) === null) {
        fail(404, "Тікет з id=$id не знайдено");
    }
    $pdo->prepare('UPDATE tickets SET status = \'closed\' WHERE id = ?')->execute([$id]);

    respond(200, ['success' => true, 'data' => findTicket($pdo, $id)]);
}

// ---------- підключення до БД ----------
try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $name = getenv('DB_NAME') ?: 'helpdesk';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';

    $pdo = new PDO(
        "mysql:host=$host;dbname=$name;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
        fail(500, 'Помилка підключення до бази даних: ' . $e->getMessage());
}

// ---------- маршрутизація ----------
$method   = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? null;
$id       = $_GET['id'] ?? null;
$action   = $_GET['action'] ?? null;

try {
    if ($resource !== 'tickets') {
        fail(404, 'Невідомий ресурс');
    }

    if ($method === 'GET') {
        if ($id === null) {
            listTickets($pdo);
        } else {
            getTicket($pdo, $id);
        }
    } elseif ($method === 'POST') {
        $input  = readInput();
        $action = $action ?? ($input['action'] ?? null);
        $id     = $id ?? ($input['id'] ?? null);

        if ($action === null) {
            createTicket($pdo, $input);
        } elseif ($action === 'close') {
            closeTicket($pdo, $id);
        } else {
            fail(400, 'Невідома дія action: ' . (string) $action);
        }
    } else {
        header('Allow: GET, POST');
        fail(405, "Метод $method не підтримується для ресурсу tickets");
    }
} catch (PDOException $e) {
    fail(500, 'Внутрішня помилка сервера');
}
