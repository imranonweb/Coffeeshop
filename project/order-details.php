<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

require_login();

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$conn = getDBConnection();

// Get order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect(SITE_URL . '/orders.php');
}

$order = $result->fetch_assoc();

// Get order items
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image FROM order_items oi 
                       JOIN products p ON oi.product_id = p.id 
                       WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="order-details-container">
        <!-- Header -->
        <div class="order-header">
            <div class="order-title">
                <h1>Order #<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></h1>
                <span class="order-date"><?php echo date('M d, Y \a\t g:i A', strtotime($order['created_at'])); ?></span>
            </div>
            <a href="orders.php" class="btn-back">← Back</a>
        </div>

        <!-- Main Content -->
        <div class="order-content">
            <!-- Left Side - Order Info -->
            <div class="order-main">
                <!-- Status Card -->
                <div class="info-card">
                    <div class="card-label">Status</div>
                    <span class="status-badge status-<?php echo $order['status']; ?>">
                        <?php 
                        $status_emoji = ['pending' => '⏳', 'confirmed' => '✅', 'preparing' => '👨‍🍳', 'ready' => '🎉', 'completed' => '✔️', 'cancelled' => '❌'];
                        echo ($status_emoji[$order['status']] ?? '') . ' ' . ucfirst($order['status']); 
                        ?>
                    </span>
                </div>

                <!-- Details Grid -->
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Order Type</div>
                        <div class="detail-value"><?php echo $order['order_type'] == 'delivery' ? '🚚 Delivery' : '🏃‍♂️ Takeout'; ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Payment</div>
                        <div class="detail-value">
                            <?php 
                            if (isset($order['payment_method'])) {
                                $payment = ucfirst($order['payment_method']);
                                if ($order['payment_method'] == 'cash') $payment = '💵 ' . $payment;
                                elseif ($order['payment_method'] == 'bkash') $payment = '📱 ' . $payment;
                                elseif ($order['payment_method'] == 'card') $payment = '💳 ' . $payment;
                                echo $payment;
                            } else {
                                echo '💵 Cash';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value"><?php echo htmlspecialchars($order['phone']); ?></div>
                    </div>
                    <?php if ($order['order_type'] == 'delivery'): ?>
                    <div class="detail-item full-width">
                        <div class="detail-label">Delivery Address</div>
                        <div class="detail-value"><?php echo htmlspecialchars($order['delivery_address']); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if ($order['notes']): ?>
                    <div class="detail-item full-width">
                        <div class="detail-label">Notes</div>
                        <div class="detail-value"><?php echo htmlspecialchars($order['notes']); ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Total Card -->
                <div class="total-card">
                    <span class="total-label">Total Amount</span>
                    <span class="total-amount"><?php echo format_price($order['total_amount']); ?></span>
                </div>
            </div>

            <!-- Right Side - Order Items -->
            <div class="order-sidebar">
                <div class="items-header">Items (<?php echo $items_result->num_rows; ?>)</div>
                <div class="items-list">
                    <?php 
                    $items_result->data_seek(0); // Reset pointer
                    while ($item = $items_result->fetch_assoc()): 
                    ?>
                        <div class="mini-item">
                            <img src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 onerror="this.src='assets/images/placeholder.png'">
                            <div class="mini-item-info">
                                <div class="mini-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="mini-item-qty">x<?php echo $item['quantity']; ?></div>
                            </div>
                            <div class="mini-item-price"><?php echo format_price($item['price'] * $item['quantity']); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
    $conn->close();
    include 'includes/footer.php'; 
    ?>
</body>
</html>
