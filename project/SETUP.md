# Coffee Shop Website - Quick Setup Guide

## 🚀 Installation Steps

### 1. Start XAMPP
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

### 2. Create Database
1. Go to: `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar
3. Create database: `coffee_shop`
4. Click on `coffee_shop` database
5. Go to **"Import"** tab
6. Choose file: `database.sql`
7. Click **"Go"**

### 3. Access Website
Open browser: `http://localhost/project/`

---

## 💰 Pricing (Bangladeshi Taka)

**Hot Coffee:**
- Espresso: ৳120
- Americano: ৳150
- Cappuccino: ৳180
- Latte: ৳180
- Mocha: ৳200

**Cold Coffee:**
- Iced Americano: ৳170
- Iced Latte: ৳190
- Cold Brew: ৳220
- Frappuccino: ৳250

**Specialty:**
- Caramel Macchiato: ৳230
- Vanilla Latte: ৳200
- Hazelnut Coffee: ৳200

**Snacks:**
- Croissant: ৳80
- Muffin: ৳100
- Bagel: ৳120
- Sandwich: ৳180

**Desserts:**
- Cookie: ৳60
- Brownie: ৳120
- Chocolate Cake: ৳150
- Cheesecake: ৳180

---

## 📸 Adding Product Images

Place your product images (400x400px recommended) in:
`assets/images/products/`

**Required image names:**
- espresso.png
- americano.png
- cappuccino.png
- latte.png
- mocha.png
- iced-americano.png
- iced-latte.png
- cold-brew.png
- frappuccino.png
- caramel-macchiato.png
- vanilla-latte.png
- hazelnut.png
- croissant.png
- muffin.png
- bagel.png
- sandwich.png
- chocolate-cake.png
- cheesecake.png
- brownie.png
- cookie.png

**Tip:** Until you add images, placeholders will be shown automatically.

---

## ✅ Features

- ✅ User registration & login
- ✅ Product catalog with categories
- ✅ Shopping cart
- ✅ Online ordering (delivery & takeout)
- ✅ Table reservations
- ✅ Order history
- ✅ Profile management
- ✅ Bangladeshi Taka (৳) currency
- ✅ Responsive design

---

## 🔧 Troubleshooting

**Database connection error?**
- Check MySQL is running in XAMPP
- Verify database name is `coffee_shop`

**Page not loading?**
- Check Apache is running
- Access via `http://localhost/project/`

**Images not showing?**
- Add PNG images to `assets/images/products/`
- Or placeholders will display automatically

---

Enjoy your Coffee Shop! ☕
