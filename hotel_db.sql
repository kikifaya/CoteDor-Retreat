CREATE DATABASE IF NOT EXISTS hotel_db;
USE hotel_db;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS subscribers;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    password VARCHAR(255) NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    size_m2 INT NOT NULL,
    max_guests INT NOT NULL,
    total_rooms INT NOT NULL,
    image VARCHAR(255),
    tagline VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT NOT NULL,
    meal_plan VARCHAR(30) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    special_request TEXT,
    total_price DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (full_name, username, email, phone, password, role) VALUES
('Hotel Admin', 'admin', 'admin@cotedorretreat.com', '+248 4 000 000', '$2y$10$5ggR/RkSiRmcJqZkVwIdb.4li/uSJPGlWuq2sSeG6FIthGlrdlBPC', 'admin'),
('Sample Guest', 'guest', 'guest@example.com', '+248 2 500 000', '$2y$10$kgRZFH9CuNh.HsYpz.S5ue7UymXiV2/4bq2fPexHDknCgnKpXQQ6G', 'user');

INSERT INTO rooms (room_name, room_type, price, size_m2, max_guests, total_rooms, image, tagline, description) VALUES
('Double Room', 'Double', 313.00, 60, 2, 10, 'room-double.jpg', 'Slow mornings, a hammock and the sound of the sea.', 'A calm 60 m² room for two with a king-size bed, a walk-in shower and a private terrace with a hammock. Air conditioning, Wi-Fi, a flat-screen TV and a mini fridge are included, and Côte d''Or Beach is a two-minute walk away.'),
('Double Room Self-Catering', 'Self-Catering', 315.00, 75, 2, 6, 'room-selfcatering.jpg', 'Your own kitchen, island produce and a deep bathtub.', 'A spacious 75 m² room with its own kitchenette, so you can cook with fresh fish and fruit from the local market. It has a bathtub, a terrace, air conditioning, Wi-Fi and a flat-screen TV.'),
('Two-Bedroom Apartment', 'Apartment', 411.00, 130, 4, 6, 'room-apartment.jpg', 'Room for the whole family, steps from the beach.', 'A 130 m² apartment with two bedrooms, a full kitchen, a living and dining area and a balcony. Ideal for families or friends travelling together, with space for four guests.');

INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, meal_plan, phone, special_request, total_price, status) VALUES
(2, 1, '2026-12-20', '2026-12-25', 2, 'Breakfast', '+248 2 500 000', 'Honeymoon, a quiet room if possible.', 1785.00, 'Confirmed'),
(2, 3, '2027-02-10', '2027-02-14', 4, 'Half board', '+248 2 500 000', '', 2892.00, 'Pending');

INSERT INTO messages (name, email, subject, message) VALUES
('Sample Guest', 'guest@example.com', 'Airport transfer', 'Hello, can you arrange a transfer from the Praslin ferry jetty on 20 December?');
