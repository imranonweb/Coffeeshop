<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

// Get all categories
$conn = getDBConnection();
$categories_result = $conn->query("SELECT * FROM categories ORDER BY name");

// Get filter
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

// Build query
$query = "SELECT p.*, c.name as category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.is_available = 1";

if ($category_filter > 0) {
    $query .= " AND p.category_id = " . $category_filter;
}

if (!empty($search)) {
    $query .= " AND (p.name LIKE '%" . $conn->real_escape_string($search) . "%' 
                OR p.description LIKE '%" . $conn->real_escape_string($search) . "%')";
}

$query .= " ORDER BY p.name";

$products_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="menu-container">
        <div class="menu-header">
            <h1>🍽️ Our Menu 🍽️</h1>
            <p>Explore our delicious selection of coffee and snacks ☕🥐</p>
        </div>
        
        <div class="menu-filters">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="🔍 Search menu..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">🔎 Search</button>
            </form>
            
            <div class="category-filters">
                <a href="menu.php" class="filter-btn <?php echo $category_filter == 0 ? 'active' : ''; ?>">⭐ All</a>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <a href="menu.php?category=<?php echo $category['id']; ?>" 
                       class="filter-btn <?php echo $category_filter == $category['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        
        <div class="products-grid">
            <?php if ($products_result->num_rows > 0): ?>
                <?php while ($product = $products_result->fetch_assoc()): ?>
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
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-products">No products found.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php 
    $conn->close();
    include 'includes/footer.php'; 
    ?>
</body>
</html>
