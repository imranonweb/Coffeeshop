# Coffeeshop
☕ Coffee Shop Web Application

A full-featured Coffee Shop Management System built with PHP, MySQL, HTML, CSS, designed to handle user authentication, product browsing, cart management, orders, and reservations.

🚀 Features
🔐 Authentication System
User Signup & Login
Secure session handling
Logout functionality
Profile management with avatar support
🛒 Shopping & Orders
Browse coffee & food items
Add/remove items from cart
Checkout system
Order history tracking
Order details view
📅 Reservation System
Table reservation booking
View personal reservations
Manage reservation data
👤 User Profile
Update user details
Upload/change avatar
View order & reservation history
💳 Payment Handling
Payment method update support
Checkout improvements (as per CHECKOUT-UPDATE.md)
🖼️ Image Handling
Product images
Avatar uploads
Image fixing script (fix_images.php)
🎨 UI/UX
Responsive layout
Clean CSS styling
Organized assets (images, styles)
🗂️ Project Structure
project/
│
├── config/
│   ├── config.php
│   └── database.php
│
├── assets/
│   ├── css/
│   └── images/
│
├── core pages:
│   ├── index.php
│   ├── menu.php
│   ├── cart.php
│   ├── checkout.php
│   ├── orders.php
│   ├── order-details.php
│   ├── reservations.php
│   ├── my-reservations.php
│
├── auth:
│   ├── login.php
│   ├── signup.php
│   └── logout.php
│
├── user:
│   └── profile.php
│
├── database:
│   ├── database.sql
│   ├── add_avatar_column.sql
│   └── update_payment_method.sql
│
└── docs:
    ├── README.md
    ├── SETUP.md
    ├── IMAGE-GUIDE.md
    └── CHECKOUT-UPDATE.md
⚙️ Installation & Setup
1️⃣ Clone or Extract Project
git clone <repo-url>

or extract ZIP into your server directory.

2️⃣ Move to Server Directory

If using XAMPP:

C:/xampp/htdocs/project

If using Laragon:

C:/laragon/www/project
3️⃣ Setup Database
Open phpMyAdmin
Create a database (e.g. coffee_shop)
Import:
database.sql
Run additional updates if needed:
add_avatar_column.sql
update_payment_method.sql
4️⃣ Configure Database Connection

Edit:

config/database.php

Update:

$host = "localhost";
$user = "root";
$password = "";
$dbname = "coffee_shop";
5️⃣ Start Server
Start Apache
Start MySQL
6️⃣ Run Application

Open browser:

http://localhost/project
🧠 Core Functional Flow
🧾 User Journey
User signs up / logs in
Browses menu (menu.php)
Adds items to cart (cart.php)
Proceeds to checkout (checkout.php)
Places order → stored in database
Views orders (orders.php)
Can reserve tables (reservations.php)
🛒 Cart System
Session-based cart storage
Dynamic add/remove items
Price calculation
📦 Order System
Orders saved in database
Each order has:
Items
Total price
Status
Detailed order view available
📅 Reservation System
Users can book tables
Stored and managed per user
Accessible via dashboard
🔧 Utility Scripts
🖼️ fix_images.php
Fixes missing/broken image paths
📄 IMAGE-GUIDE.md
Explains how images are structured and used
📄 CHECKOUT-UPDATE.md
Details enhancements in checkout system
🛡️ Security Notes
Basic session authentication implemented
Input validation required for production
Recommended improvements:
Password hashing (bcrypt)
Prepared statements (if not already used)
CSRF protection
💡 Future Improvements
Admin dashboard
Online payment integration (SSLCommerz / Stripe)
Email notifications
Order status tracking (real-time)
API-based architecture (for mobile app)
Better UI (React frontend)
🧑‍💻 Tech Stack
Frontend: HTML, CSS
Backend: PHP
Database: MySQL
Server: Apache (XAMPP/Laragon)
