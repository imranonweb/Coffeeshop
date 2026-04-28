<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Premium Coffee & Snacks</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>☕ Welcome to Coffee Haven ☕</h1>
            <p>Experience the finest coffee and delicious treats 🍰✨</p>
            <div class="hero-buttons">
                <a href="menu.php" class="btn btn-primary">🛒 Order Now</a>
                <a href="reservations.php" class="btn btn-secondary">📅 Book a Table</a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">☕</div>
                    <h3>☕ Premium Coffee</h3>
                    <p>Handcrafted espresso drinks made with the finest beans 🌟</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚚</div>
                    <h3>🚚 Fast Delivery</h3>
                    <p>Quick delivery to your doorstep or pickup at our store 🏃‍♂️</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🍰</div>
                    <h3>🍰 Fresh Snacks</h3>
                    <p>Delicious pastries and snacks baked fresh daily 🥐</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>📅 Easy Booking</h3>
                    <p>Reserve your favorite table with just a few clicks ✨</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Popular Items Section -->
    <section class="popular-items">
        <div class="container">
            <h2>⭐ Popular Items ⭐</h2>
            <p class="section-subtitle">Try our customer favorites 💕</p>
            
            <div class="products-grid">
                <?php
                $conn = getDBConnection();
                $result = $conn->query("SELECT p.*, c.name as category_name FROM products p 
                                       LEFT JOIN categories c ON p.category_id = c.id 
                                       WHERE p.is_available = 1 
                                       ORDER BY RAND() 
                                       LIMIT 6");
                
                while ($product = $result->fetch_assoc()):
                ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 onerror="this.src='assets/images/placeholder.png'">
                        </div>
                        <div class="product-info">
                            <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-footer">
                                <span class="price"><?php echo format_price($product['price']); ?></span>
                                <form method="POST" action="cart.php" class="add-to-cart-form">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php 
                endwhile;
                $conn->close();
                ?>
            </div>
            
            <div class="text-center">
                <a href="menu.php" class="btn btn-primary">View Full Menu</a>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>About Coffee Haven</h2>
                    <p>We are passionate about serving the perfect cup of coffee. Our skilled baristas use premium beans sourced from around the world to create exceptional drinks that delight your senses.</p>
                    <p>Whether you're looking for a quick caffeine fix, a cozy spot to work, or a place to meet friends, Coffee Haven is your destination for quality coffee and warm hospitality.</p>
                </div>
                <div class="about-image">
                    <img src="assets/images/about.png" alt="Coffee Haven" onerror="this.src='assets/images/placeholder.png'">
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
