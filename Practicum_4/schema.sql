CREATE DATABASE IF NOT EXISTS practicum4 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE practicum4;

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'closed') NOT NULL DEFAULT 'open'
);

INSERT INTO tickets (subject, priority, status) VALUES
('Не працює вхід у особистий кабінет', 'high', 'open'),
('Помилка при завантаженні файлу', 'medium', 'in_progress'),
('Змінити тему інтерфейсу', 'low', 'closed');
