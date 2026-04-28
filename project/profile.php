<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

require_login();

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$conn = getDBConnection();

// Handle avatar upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];
    
    // Validate file
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        if (!in_array($file['type'], $allowed_types)) {
            $error = 'Invalid file type. Only JPG, PNG, and GIF allowed.';
        } elseif ($file['size'] > $max_size) {
            $error = 'File too large. Maximum size is 5MB.';
        } else {
            // Create avatars directory if not exists
            $upload_dir = 'assets/images/avatars/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $user_id . '_' . time() . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Delete old avatar if exists
                $old_avatar = get_logged_in_user()['avatar'] ?? '';
                if ($old_avatar && file_exists('assets/images/avatars/' . $old_avatar)) {
                    unlink('assets/images/avatars/' . $old_avatar);
                }
                
                // Update database
                $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                $stmt->bind_param("si", $filename, $user_id);
                
                if ($stmt->execute()) {
                    $success = 'Avatar updated successfully!';
                } else {
                    $error = 'Failed to update database.';
                    unlink($filepath); // Remove uploaded file
                }
            } else {
                $error = 'Failed to upload file.';
            }
        }
    } else {
        $error = 'Upload error: ' . $file['error'];
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitize_input($_POST['full_name']);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);
    
    if (empty($full_name)) {
        $error = 'Full name is required';
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssi", $full_name, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Profile updated successfully!';
        } else {
            $error = 'Failed to update profile.';
        }
    }
}

// Get user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="checkout-container">
        <h1>👤 My Profile</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="profile-layout">
            <!-- Avatar Section -->
            <div class="profile-sidebar">
                <div class="avatar-section">
                    <div class="avatar-wrapper">
                        <?php if (!empty($user['avatar']) && file_exists('assets/images/avatars/' . $user['avatar'])): ?>
                            <img src="assets/images/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="avatar-image">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <span><?php echo strtoupper(substr($user['full_name'] ?? $user['username'], 0, 1)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data" class="avatar-upload-form">
                        <label for="avatar" class="btn btn-secondary btn-small">📷 Change Avatar</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="this.form.submit()">
                    </form>
                    
                    <div class="user-info-card">
                        <h3><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></h3>
                        <p class="user-email">📧 <?php echo htmlspecialchars($user['email']); ?></p>
                        <p class="user-since">📅 Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Profile Form -->
            <div class="profile-main">
                <form method="POST" action="" class="checkout-form">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="checkout-section">
                        <h2>📝 Personal Information</h2>
                        
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small>Email cannot be changed</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                   placeholder="+880 1XXX-XXXXXX">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="3" 
                                      placeholder="House/Flat, Street, Area, City"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                    </div>
                </form>
                
                <!-- Quick Links -->
                <div class="checkout-section">
                    <h2>🔗 Quick Links</h2>
                    <div class="quick-links">
                        <a href="orders.php" class="quick-link-card">
                            <span class="quick-link-icon">📦</span>
                            <span class="quick-link-text">My Orders</span>
                        </a>
                        <a href="my-reservations.php" class="quick-link-card">
                            <span class="quick-link-icon">📅</span>
                            <span class="quick-link-text">My Reservations</span>
                        </a>
                        <a href="cart.php" class="quick-link-card">
                            <span class="quick-link-icon">🛒</span>
                            <span class="quick-link-text">Shopping Cart</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
