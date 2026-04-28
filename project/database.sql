-- Coffee Shop Database Schema
CREATE DATABASE IF NOT EXISTS coffee_shop;
USE coffee_shop;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_type ENUM('delivery', 'takeout') NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('cash', 'bkash', 'card') DEFAULT 'cash',
    delivery_address TEXT,
    phone VARCHAR(20),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    number_of_people INT NOT NULL,
    table_number INT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    special_requests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES
('Hot Coffee', 'Freshly brewed hot coffee selections', 'hot-coffee.jpg'),
('Cold Coffee', 'Refreshing iced coffee drinks', 'cold-coffee.jpg'),
('Specialty Drinks', 'Unique coffee creations', 'specialty.jpg'),
('Snacks', 'Delicious snacks and pastries', 'snacks.jpg'),
('Desserts', 'Sweet treats and desserts', 'desserts.jpg');

-- Insert sample products (Realistic Bangladeshi Prices)
INSERT INTO products (name, description, price, category_id, image, stock) VALUES
-- Hot Coffee
('Espresso', 'Rich and bold espresso shot', 120.00, 1, 'espresso.png', 100),
('Americano', 'Espresso with hot water', 150.00, 1, 'americano.png', 100),
('Cappuccino', 'Espresso with steamed milk and foam', 180.00, 1, 'cappuccino.png', 100),
('Latte', 'Espresso with steamed milk', 180.00, 1, 'latte.png', 100),
('Mocha', 'Espresso with chocolate and steamed milk', 200.00, 1, 'mocha.png', 100),

-- Cold Coffee
('Iced Americano', 'Cold espresso with water and ice', 170.00, 2, 'iced-americano.png', 100),
('Iced Latte', 'Espresso with cold milk and ice', 190.00, 2, 'iced-latte.png', 100),
('Cold Brew', 'Smooth cold-brewed coffee', 220.00, 2, 'cold-brew.png', 100),
('Frappuccino', 'Blended iced coffee drink', 250.00, 2, 'frappuccino.png', 100),

-- Specialty Drinks
('Caramel Macchiato', 'Vanilla, caramel, and espresso', 230.00, 3, 'caramel-macchiato.png', 100),
('Vanilla Latte', 'Latte with vanilla syrup', 200.00, 3, 'vanilla-latte.png', 100),
('Hazelnut Coffee', 'Coffee with hazelnut flavor', 200.00, 3, 'hazelnut.png', 100),

-- Snacks
('Croissant', 'Buttery flaky pastry', 80.00, 4, 'croissant.png', 50),
('Blueberry Muffin', 'Fresh baked muffin', 100.00, 4, 'muffin.png', 50),
('Bagel with Cream Cheese', 'Toasted bagel', 120.00, 4, 'bagel.png', 50),
('Sandwich', 'Fresh made sandwich', 180.00, 4, 'sandwich.png', 50),

-- Desserts
('Chocolate Cake', 'Rich chocolate cake slice', 150.00, 5, 'chocolate-cake.png', 30),
('Cheesecake', 'Creamy cheesecake slice', 180.00, 5, 'cheesecake.png', 30),
('Brownie', 'Fudgy chocolate brownie', 120.00, 5, 'brownie.png', 30),
('Cookie', 'Freshly baked cookie', 60.00, 5, 'cookie.png', 50);
