<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

require_login();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_date = sanitize_input($_POST['reservation_date']);
    $reservation_time = sanitize_input($_POST['reservation_time']);
    $number_of_people = (int)$_POST['number_of_people'];
    $special_requests = sanitize_input($_POST['special_requests']);
    
    if (empty($reservation_date) || empty($reservation_time) || $number_of_people < 1) {
        $error = 'Please fill in all required fields';
    } else {
        $user_id = $_SESSION['user_id'];
        $conn = getDBConnection();
        
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, reservation_date, reservation_time, number_of_people, special_requests) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $user_id, $reservation_date, $reservation_time, $number_of_people, $special_requests);
        
        if ($stmt->execute()) {
            $success = 'Reservation request submitted successfully! We will confirm shortly.';
        } else {
            $error = 'Failed to submit reservation. Please try again.';
        }
        
        $stmt->close();
        $conn->close();
    }
}

// Get minimum date (today)
$min_date = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reservation - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="reservation-container">
        <div class="reservation-header">
            <h1>Book a Table</h1>
            <p>Reserve your spot at our cozy coffee shop</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="reservation-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="reservation_date">Date *</label>
                    <input type="date" id="reservation_date" name="reservation_date" 
                           min="<?php echo $min_date; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="reservation_time">Time *</label>
                    <input type="time" id="reservation_time" name="reservation_time" 
                           min="08:00" max="20:00" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="number_of_people">Number of People *</label>
                <input type="number" id="number_of_people" name="number_of_people" 
                       min="1" max="20" required>
            </div>
            
            <div class="form-group">
                <label for="special_requests">Special Requests (optional)</label>
                <textarea id="special_requests" name="special_requests" rows="4" 
                          placeholder="Any dietary restrictions, special occasions, etc..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary btn-large">Book Reservation</button>
        </form>
        
        <div class="reservation-info">
            <h3>Reservation Policy</h3>
            <ul>
                <li>Reservations are available from 8:00 AM to 8:00 PM</li>
                <li>Please arrive within 15 minutes of your reservation time</li>
                <li>For parties of 10 or more, please call us directly</li>
                <li>Cancellations must be made at least 2 hours in advance</li>
            </ul>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
