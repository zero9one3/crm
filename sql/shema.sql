DATABASE SCHEMA (MariaDB)


CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
email VARCHAR(100) UNIQUE,
password VARCHAR(255),
role ENUM('admin','master') NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE clients (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
phone VARCHAR(20) UNIQUE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE orders (
id INT AUTO_INCREMENT PRIMARY KEY,
client_id INT,
master_id INT,
device VARCHAR(100),
problem TEXT,
price DECIMAL(10,2),
cost DECIMAL(10,2),
status ENUM('new','in_progress','done','issued'),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (client_id) REFERENCES clients(id),
FOREIGN KEY (master_id) REFERENCES users(id)
);


CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_clients_phone ON clients(phone);