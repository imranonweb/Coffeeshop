<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

require_login();

$conn = getDBConnection();
$user_id = $_SESSION['user_id'];

// Get user's orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="orders-container">
        <h1>My Orders</h1>
        
        <?php if ($flash = get_flash_message()): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($orders_result->num_rows > 0): ?>
            <div class="orders-list">
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <h3>Order #<?php echo $order['id']; ?></h3>
                                <p class="order-date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                            </div>
                            <span class="order-status status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                        <div class="order-details">
                            <p><strong>Type:</strong> <?php echo ucfirst($order['order_type']); ?></p>
                            <p><strong>Payment:</strong> 
                                <?php 
                                if (isset($order['payment_method'])) {
                                    echo ucfirst($order['payment_method']);
                                    if ($order['payment_method'] == 'cash') echo ' 💵';
                                    elseif ($order['payment_method'] == 'bkash') echo ' 📱';
                                    elseif ($order['payment_method'] == 'card') echo ' 💳';
                                } else {
                                    echo 'Cash 💵';
                                }
                                ?>
                            </p>
                            <p><strong>Total:</strong> <?php echo format_price($order['total_amount']); ?></p>
                            <?php if ($order['order_type'] == 'delivery'): ?>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                            <?php endif; ?>
                        </div>
                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary">View Details</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>You haven't placed any orders yet.</p>
                <a href="menu.php" class="btn btn-primary">Browse Menu</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php 
    $conn->close();
    include 'includes/footer.php'; 
    ?>
</body>
</html>
