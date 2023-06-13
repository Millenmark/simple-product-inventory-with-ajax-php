DROP DATABASE IF EXISTS product_inventory;

CREATE DATABASE product_inventory;

USE product_inventory;

CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  unit VARCHAR(50),
  price FLOAT,
  expiration_date DATE,
  stocks INT,
  image VARCHAR(255)
);
