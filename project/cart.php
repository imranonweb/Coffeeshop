<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'add') {
        $product_id = (int)$_POST['product_id'];
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 0;
        }
        $_SESSION['cart'][$product_id]++;
        set_flash_message('Product added to cart!');
        redirect(SITE_URL . '/cart.php');
    } elseif ($action == 'update') {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
        set_flash_message('Cart updated!');
        redirect(SITE_URL . '/cart.php');
    } elseif ($action == 'remove') {
        $product_id = (int)$_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
        set_flash_message('Product removed from cart!');
        redirect(SITE_URL . '/cart.php');
    } elseif ($action == 'clear') {
        $_SESSION['cart'] = [];
        set_flash_message('Cart cleared!');
        redirect(SITE_URL . '/cart.php');
    }
}

// Get cart items
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $conn = getDBConnection();
    $ids = array_keys($_SESSION['cart']);
    $ids_str = implode(',', array_map('intval', $ids));
    
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids_str)");
    
    while ($product = $result->fetch_assoc()) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['price'] * $product['quantity'];
        $total += $product['subtotal'];
        $cart_items[] = $product;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="cart-container">
        <div class="cart-header">
            <h1>🛒 Shopping Cart</h1>
            <?php if (!empty($cart_items)): ?>
                <span class="cart-count"><?php echo count($cart_items); ?> item<?php echo count($cart_items) > 1 ? 's' : ''; ?></span>
            <?php endif; ?>
        </div>
        
        <?php if ($flash = get_flash_message()): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <a href="menu.php" class="btn btn-primary btn-large">☕ Browse Menu</a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.png'">
                            </div>
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="item-price">💰 <?php echo format_price($item['price']); ?> each</p>
                            </div>
                            <div class="item-quantity">
                                <form method="POST" action="cart.php" class="quantity-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <div class="quantity-control">
                                        <label>Quantity:</label>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99">
                                        <button type="submit" class="btn btn-small">✓ Update</button>
                                    </div>
                                </form>
                            </div>
                            <div class="item-subtotal">
                                <span class="subtotal-label">Subtotal:</span>
                                <span class="subtotal-amount"><?php echo format_price($item['subtotal']); ?></span>
                            </div>
                            <form method="POST" action="cart.php" class="item-remove">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn-remove" title="Remove item">🗑️</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <h2>📋 Order Summary</h2>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Items (<?php echo count($cart_items); ?>):</span>
                            <span><?php echo format_price($total); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>🚚 Delivery Fee:</span>
                            <span class="note-text">Calculated at checkout</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span class="total-amount"><?php echo format_price($total); ?></span>
                        </div>
                    </div>
                    <a href="checkout.php" class="btn btn-primary btn-block">🛍️ Proceed to Checkout</a>
                    <a href="menu.php" class="btn btn-secondary btn-block">🍰 Continue Shopping</a>
                    <form method="POST" action="cart.php" style="margin-top: 1rem;">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-text btn-block" onclick="return confirm('Clear all items from cart?')">🗑️ Clear Cart</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
