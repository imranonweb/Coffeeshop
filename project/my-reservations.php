<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

require_login();

$conn = getDBConnection();
$user_id = $_SESSION['user_id'];

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_id'])) {
    $cancel_id = (int)$_POST['cancel_id'];
    $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cancel_id, $user_id);
    $stmt->execute();
    set_flash_message('Reservation cancelled successfully');
    redirect(SITE_URL . '/my-reservations.php');
}

// Get user's reservations
$stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ? ORDER BY reservation_date DESC, reservation_time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reservations_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="reservations-container">
        <h1>My Reservations</h1>
        
        <?php if ($flash = get_flash_message()): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($reservations_result->num_rows > 0): ?>
            <div class="reservations-list">
                <?php while ($reservation = $reservations_result->fetch_assoc()): ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <div>
                                <h3>Reservation #<?php echo $reservation['id']; ?></h3>
                                <p class="reservation-datetime">
                                    <?php echo date('F d, Y', strtotime($reservation['reservation_date'])); ?> 
                                    at <?php echo date('g:i A', strtotime($reservation['reservation_time'])); ?>
                                </p>
                            </div>
                            <span class="reservation-status status-<?php echo $reservation['status']; ?>">
                                <?php echo ucfirst($reservation['status']); ?>
                            </span>
                        </div>
                        <div class="reservation-details">
                            <p><strong>Party Size:</strong> <?php echo $reservation['number_of_people']; ?> people</p>
                            <?php if ($reservation['table_number']): ?>
                                <p><strong>Table:</strong> #<?php echo $reservation['table_number']; ?></p>
                            <?php endif; ?>
                            <?php if ($reservation['special_requests']): ?>
                                <p><strong>Special Requests:</strong> <?php echo htmlspecialchars($reservation['special_requests']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($reservation['status'] == 'pending' || $reservation['status'] == 'confirmed'): ?>
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="cancel_id" value="<?php echo $reservation['id']; ?>">
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                    Cancel Reservation
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>You don't have any reservations yet.</p>
                <a href="reservations.php" class="btn btn-primary">Book a Table</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php 
    $conn->close();
    include 'includes/footer.php'; 
    ?>
</body>
</html>
