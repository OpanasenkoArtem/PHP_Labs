SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS helpdesk CHARACTER SET utf8mb4;
USE helpdesk;

CREATE TABLE IF NOT EXISTS tickets (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    subject  VARCHAR(255) NOT NULL,
    priority ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
    status   ENUM('open','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tickets (subject, priority, status) VALUES
 ('Не працює вхід у кабінет', 'high',   'open'),
 ('Запит на новий пароль',    'low',    'open'),
 ('Помилка при оплаті',       'medium', 'closed');