SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    priority ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
    status ENUM('open','in_progress','closed') NOT NULL DEFAULT 'open'
) DEFAULT CHARSET=utf8mb4;

INSERT INTO tickets (subject, priority, status) VALUES
('Не працює вхід в особистий кабінет', 'high', 'open'),
('Помилка при оплаті замовлення', 'high', 'in_progress'),
('Змінити email в профілі', 'low', 'closed'),
('Повільно завантажується сторінка', 'medium', 'open');