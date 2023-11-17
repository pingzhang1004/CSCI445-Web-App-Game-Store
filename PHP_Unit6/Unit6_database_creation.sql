USE pingzhang;

DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS product;

CREATE TABLE Customer (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255)
);

CREATE TABLE Product (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255),
    image_name VARCHAR(255),
    price DECIMAL(6,2),
    puzzle_pieces INT,
    in_stock INT,
    inactive TINYINT DEFAULT 0  -- Assuming '0' means the product is active by default
);

CREATE TABLE Orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    customer_id INT,
    quantity INT,
    price DECIMAL(6,2),
    tax DECIMAL(6,2),
    donation DECIMAL(4,2),
    time_stamp BIGINT,
    FOREIGN KEY (product_id) REFERENCES Product(id),
    FOREIGN KEY (customer_id) REFERENCES Customer(id)
);

CREATE TABLE Users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  password VARCHAR(50),
  email VARCHAR(100) UNIQUE,
  role INT
);

INSERT INTO Users (first_name, last_name, password, email, role) VALUES
('Frodo', 'Baggins', 'fb', 'fb@mines.edu', 1),
('Harry', 'Potter', 'hp', 'hp@mines.edu', 2);


INSERT INTO Customer (id, first_name, last_name, email)
VALUES (1, 'Mickey', 'Mouse', 'mmouse@mines.edu'),
       (2, 'Andong', 'Ma', 'adma@tamu.edu');


INSERT INTO Product (id, product_name, image_name, price, puzzle_pieces, in_stock)
VALUES (1, 'Mermaid Puzzle', 'puzzle_Mermaid.png', 9.99, 30, 2),
       (2, 'Ocean Puzzle', 'puzzle_Ocean.png', 14.99, 50, 0),
       (3, 'Pony Puzzle', 'puzzle_Pony.png', 19.99, 100, 300);


