<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Haven - Header</title>
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="nav-container">
                <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                    <span class="logo-icon">☕</span>
                    <?php echo SITE_NAME; ?>
                </a>
                
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <ul class="nav-menu">
                    <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/menu.php">Menu</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/reservations.php">Reservations</a></li>
                    
                    <?php if (is_logged_in()): ?>
                        <li><a href="<?php echo SITE_URL; ?>/orders.php">My Orders</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/my-reservations.php">My Reservations</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/cart.php" class="cart-link">
                            🛒 Cart 
                            <?php $cart_count = get_cart_count(); if ($cart_count > 0): ?>
                                <span class="cart-badge"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <?php echo htmlspecialchars(get_logged_in_user()['username']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo SITE_URL; ?>/profile.php">Profile</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo SITE_URL; ?>/cart.php" class="cart-link">
                            🛒 Cart 
                            <?php $cart_count = get_cart_count(); if ($cart_count > 0): ?>
                                <span class="cart-badge"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/login.php" class="btn btn-small">Login</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/signup.php" class="btn btn-primary btn-small">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
            this.classList.toggle('active');
        });
    </script>
</body>
</html>
