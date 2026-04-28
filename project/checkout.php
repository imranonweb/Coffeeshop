<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

require_login();

if (empty($_SESSION['cart'])) {
    redirect(SITE_URL . '/cart.php');
}

$error = '';
$user = get_logged_in_user();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_type = sanitize_input($_POST['order_type']);
    $delivery_address = sanitize_input($_POST['delivery_address']);
    $phone = sanitize_input($_POST['phone']);
    $notes = sanitize_input($_POST['notes']);
    $payment_method = sanitize_input($_POST['payment_method']);
    
    if (empty($order_type) || empty($phone) || empty($payment_method)) {
        $error = 'Please fill in all required fields';
    } elseif ($order_type == 'delivery' && empty($delivery_address)) {
        $error = 'Please provide a delivery address';
    } else {
        $conn = getDBConnection();
        $cart_subtotal = get_cart_total();
        // Add delivery fee if delivery order
        $delivery_fee = ($order_type == 'delivery') ? 50 : 0;
        $total = $cart_subtotal + $delivery_fee;
        $user_id = $_SESSION['user_id'];
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_type, delivery_address, phone, notes, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("idsssss", $user_id, $total, $order_type, $delivery_address, $phone, $notes, $payment_method);
            $stmt->execute();
            $order_id = $conn->insert_id;
            
            // Insert order items
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $product['price']);
                $stmt->execute();
            }
            
            // Commit transaction
            $conn->commit();
            
            // Clear cart
            $_SESSION['cart'] = [];
            
            set_flash_message('Order placed successfully! Order ID: #' . $order_id);
            redirect(SITE_URL . '/orders.php');
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Failed to place order. Please try again.';
        }
        
        $conn->close();
    }
}

$cart_total = get_cart_total();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>🛒 Checkout</h1>
            <p class="checkout-subtitle">Complete your order in just a few steps</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="checkout-layout">
            <div class="checkout-main">
                <form method="POST" action="" class="checkout-form">
                    
                    <!-- Order Type Section -->
                    <div class="checkout-section">
                        <div class="section-header">
                            <span class="section-number">1</span>
                            <h2>📦 Order Type</h2>
                        </div>
                        <div class="radio-cards">
                            <label class="radio-card">
                                <input type="radio" name="order_type" value="delivery" required>
                                <div class="radio-card-content">
                                    <div class="radio-card-icon">🚚</div>
                                    <div class="radio-card-title">Delivery</div>
                                    <div class="radio-card-desc">Get it delivered to your door</div>
                                </div>
                            </label>
                            <label class="radio-card">
                                <input type="radio" name="order_type" value="takeout" required>
                                <div class="radio-card-content">
                                    <div class="radio-card-icon">🏃‍♂️</div>
                                    <div class="radio-card-title">Takeout</div>
                                    <div class="radio-card-desc">Pick up from our store</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="checkout-section">
                        <div class="section-header">
                            <span class="section-number">2</span>
                            <h2>📞 Contact Information</h2>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">📱 Phone Number *</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                       placeholder="+880 1XXX-XXXXXX" required>
                            </div>
                        </div>
                        
                        <div class="form-group" id="address-group" style="display:none;">
                            <label for="delivery_address">📍 Delivery Address *</label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" 
                                      placeholder="House/Flat, Street, Area, City"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">📝 Order Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="2" 
                                      placeholder="Any special instructions?"></textarea>
                        </div>
                    </div>
                    
                    <!-- Payment Method Section -->
                    <div class="checkout-section">
                        <div class="section-header">
                            <span class="section-number">3</span>
                            <h2>💳 Payment Method</h2>
                        </div>
                        <div class="payment-methods">
                            <label class="payment-card">
                                <input type="radio" name="payment_method" value="cash" required>
                                <div class="payment-card-content">
                                    <div class="payment-icon">💵</div>
                                    <div class="payment-details">
                                        <div class="payment-title">Cash on Delivery</div>
                                        <div class="payment-desc">Pay when you receive</div>
                                    </div>
                                    <div class="payment-check">✓</div>
                                </div>
                            </label>
                            
                            <label class="payment-card">
                                <input type="radio" name="payment_method" value="bkash" required>
                                <div class="payment-card-content">
                                    <div class="payment-icon bkash-icon">📱</div>
                                    <div class="payment-details">
                                        <div class="payment-title">bKash</div>
                                        <div class="payment-desc">Mobile banking payment</div>
                                    </div>
                                    <div class="payment-check">✓</div>
                                </div>
                            </label>
                            
                            <label class="payment-card">
                                <input type="radio" name="payment_method" value="card" required>
                                <div class="payment-card-content">
                                    <div class="payment-icon">💳</div>
                                    <div class="payment-details">
                                        <div class="payment-title">Credit/Debit Card</div>
                                        <div class="payment-desc">Visa, Mastercard accepted</div>
                                    </div>
                                    <div class="payment-check">✓</div>
                                </div>
                            </label>
                        </div>
                        
                        <div id="bkash-info" class="payment-info" style="display:none;">
                            <div class="info-box">
                                <strong>📱 bKash Payment:</strong>
                                <p>🔜 Will be available soon</p>
                                <p class="note">Online bKash payment integration coming soon!</p>
                            </div>
                        </div>
                        
                        <div id="card-info" class="payment-info" style="display:none;">
                            <div class="info-box">
                                <strong>💳 Card Payment:</strong>
                                <p>🔜 Will be available soon</p>
                                <p class="note">Online card payment integration coming soon!</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large btn-checkout">
                        🛍️ Place Order - <?php echo format_price($cart_total); ?>
                    </button>
                </form>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="checkout-sidebar">
                <div class="order-summary-card">
                    <h3>📋 Order Summary</h3>
                    
                    <div class="summary-items">
                        <?php
                        $conn = getDBConnection();
                        $item_count = 0;
                        foreach ($_SESSION['cart'] as $product_id => $quantity):
                            $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $product = $result->fetch_assoc();
                            $item_count++;
                        ?>
                            <div class="summary-item">
                                <div class="item-info">
                                    <div class="item-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <div class="item-qty">Qty: <?php echo $quantity; ?></div>
                                </div>
                                <div class="item-price"><?php echo format_price($product['price'] * $quantity); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row">
                        <span>Subtotal (<?php echo $item_count; ?> items):</span>
                        <span><?php echo format_price($cart_total); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span class="delivery-fee">৳0</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Amount:</span>
                        <span class="total-amount"><?php echo format_price($cart_total); ?></span>
                    </div>
                    
                    <div class="secure-checkout">
                        <span class="secure-icon">🔒</span>
                        <span>Secure Checkout</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Order type toggle
        document.querySelectorAll('input[name="order_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const addressGroup = document.getElementById('address-group');
                const addressField = document.getElementById('delivery_address');
                const deliveryFee = document.querySelector('.delivery-fee');
                
                if (this.value === 'delivery') {
                    addressGroup.style.display = 'block';
                    addressField.required = true;
                    deliveryFee.textContent = '৳50';
                    updateTotal(50);
                } else {
                    addressGroup.style.display = 'none';
                    addressField.required = false;
                    deliveryFee.textContent = '৳0';
                    updateTotal(0);
                }
                
                // Add active class to selected card
                document.querySelectorAll('.radio-card').forEach(card => {
                    card.classList.remove('active');
                });
                this.closest('.radio-card').classList.add('active');
            });
        });
        
        // Payment method toggle
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Hide all payment info
                document.querySelectorAll('.payment-info').forEach(info => {
                    info.style.display = 'none';
                });
                
                // Show selected payment info
                if (this.value === 'bkash') {
                    document.getElementById('bkash-info').style.display = 'block';
                } else if (this.value === 'card') {
                    document.getElementById('card-info').style.display = 'block';
                }
                
                // Add active class to selected payment card
                document.querySelectorAll('.payment-card').forEach(card => {
                    card.classList.remove('active');
                });
                this.closest('.payment-card').classList.add('active');
            });
        });
        
        // Update total with delivery fee
        function updateTotal(deliveryFee) {
            const subtotal = <?php echo $cart_total; ?>;
            const total = subtotal + deliveryFee;
            document.querySelector('.total-amount').textContent = '৳' + total.toFixed(2);
            
            // Update button text
            const btn = document.querySelector('.btn-checkout');
            btn.innerHTML = '🛍️ Place Order - ৳' + total.toFixed(2);
        }
    </script>
</body>
</html>
