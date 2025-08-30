CREATE DATABASE IF NOT EXISTS challenge;
USE challenge;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', 'securepassword');

-- Solución: Permitir conexión desde localhost Y desde cualquier host
CREATE USER IF NOT EXISTS 'ctfuser'@'127.0.0.1' IDENTIFIED BY 'ctfpass';
CREATE USER IF NOT EXISTS 'ctfuser'@'localhost' IDENTIFIED BY 'ctfpass';
CREATE USER IF NOT EXISTS 'ctfuser'@'%' IDENTIFIED BY 'ctfpass';
GRANT ALL PRIVILEGES ON challenge.* TO 'ctfuser'@'127.0.0.1';
GRANT ALL PRIVILEGES ON challenge.* TO 'ctfuser'@'localhost';
GRANT ALL PRIVILEGES ON challenge.* TO 'ctfuser'@'%';
FLUSH PRIVILEGES;

