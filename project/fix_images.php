<?php
require_once 'config/database.php';

$conn = getDBConnection();

// Update image filenames to match uploaded files
$updates = [
    "UPDATE products SET image = 'blueberry-muffin.png' WHERE name = 'Blueberry Muffin'",
    "UPDATE products SET image = 'cookies.png' WHERE name = 'Cookie'"
];

foreach ($updates as $sql) {
    if ($conn->query($sql)) {
        echo "✓ Updated successfully\n";
    } else {
        echo "✗ Error: " . $conn->error . "\n";
    }
}

echo "\n📋 Updated Products:\n";
$result = $conn->query("SELECT name, image FROM products WHERE name IN ('Blueberry Muffin', 'Cookie', 'Hazelnut Coffee', 'Chocolate Cake')");
while ($row = $result->fetch_assoc()) {
    echo "  • {$row['name']} -> {$row['image']}\n";
}

$conn->close();
?>
