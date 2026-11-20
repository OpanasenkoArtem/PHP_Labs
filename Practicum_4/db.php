<?php
$dsn = 'mysql:host=127.0.0.1;port=3307;dbname=practicum4;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

return $pdo;