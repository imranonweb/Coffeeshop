# Coffee Shop Website - Installation Guide

## Requirements
- XAMPP (Apache + MySQL + PHP)
- Web browser
- Text editor (optional)

## Installation Steps

### 1. Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** server
3. Start **MySQL** server

### 2. Create Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on "New" in the left sidebar
3. Create a new database named `coffee_shop`
4. Click on the `coffee_shop` database
5. Click on "Import" tab
6. Click "Choose File" and select `database.sql` from the project folder
7. Click "Go" to import the database

**OR**

1. Go to `http://localhost/phpmyadmin`
2. Click on "SQL" tab
3. Copy and paste the entire content from `database.sql`
4. Click "Go"

### 3. Access the Website
Open your browser and navigate to: `http://localhost/project`

## Default Setup
- Database Host: `localhost`
- Database User: `root`
- Database Password: (empty)
- Database Name: `coffee_shop`

## Features
✅ User Registration & Login
✅ Product Catalog (Coffee & Snacks)
✅ Shopping Cart
✅ Online Ordering (Delivery & Takeout)
✅ Table Reservations
✅ Order History
✅ User Profile Management

## Testing the Website

### 1. Create an Account
- Click "Sign Up" in the navigation
- Fill in the registration form
- Login with your credentials

### 2. Browse Menu
- Click "Menu" to view all products
- Filter by category
- Search for specific items

### 3. Place an Order
- Add items to cart
- Go to cart and review items
- Proceed to checkout
- Choose delivery or takeout
- Complete your order

### 4. Make a Reservation
- Click "Reservations"
- Select date, time, and number of people
- Submit reservation request

### 5. View Order History
- Click "My Orders" to see all your orders
- Click on any order to view details

## Customization

### Update Site Name
Edit `config/config.php`:
```php
define('SITE_NAME', 'Your Coffee Shop Name');
```

### Update Database Credentials
Edit `config/database.php` if your MySQL setup is different:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'coffee_shop');
```

### Add Product Images
1. Place product images in `assets/images/products/` folder
2. Images should match the filenames in the database (e.g., `espresso.jpg`, `latte.jpg`)
3. Recommended size: 400x400 pixels
4. Supported formats: JPG, PNG, WEBP

## Troubleshooting

### "Database connection failed"
- Make sure MySQL is running in XAMPP
- Verify database credentials in `config/database.php`
- Ensure `coffee_shop` database exists

### "Page not found" or 404 errors
- Check that files are in `C:\xampp\htdocs\project\`
- Access via `http://localhost/project/`

### Images not showing
- Place a placeholder image: `assets/images/placeholder.jpg`
- Or add actual product images to `assets/images/products/`

### Session errors
- Make sure cookies are enabled in your browser
- Try clearing browser cache

## Project Structure
```
project/
├── config/
│   ├── config.php          # Site configuration
│   └── database.php        # Database connection
├── includes/
│   ├── header.php          # Header template
│   ├── footer.php          # Footer template
│   └── functions.php       # Helper functions
├── assets/
│   ├── css/
│   │   └── style.css       # Main stylesheet
│   └── images/             # Image files
├── index.php               # Homepage
├── menu.php                # Product catalog
├── cart.php                # Shopping cart
├── checkout.php            # Checkout page
├── orders.php              # Order history
├── order-details.php       # Order details
├── reservations.php        # Book reservation
├── my-reservations.php     # Reservation history
├── login.php               # Login page
├── signup.php              # Registration page
├── logout.php              # Logout handler
├── profile.php             # User profile
└── database.sql            # Database schema

```

## Support
For issues or questions, check:
- Database is imported correctly
- XAMPP services are running
- File permissions are correct
- PHP errors in Apache error logs

## Security Notes for Production
Before deploying to production:
1. Change database password
2. Disable error reporting in `config/config.php`
3. Use HTTPS
4. Add CSRF protection
5. Implement rate limiting
6. Sanitize all inputs thoroughly
7. Use prepared statements (already implemented)

Enjoy your Coffee Shop Website! ☕
