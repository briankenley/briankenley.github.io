<?php
include 'db.php'; // Include your database connection script

// SQL statement to add the 'role' column if it doesn't exist
$sql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('user', 'admin') DEFAULT 'user' AFTER password";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' altered successfully (or column already exists).\n";
} else {
    echo "Error altering table 'users': " . $conn->error . "\n";
}

$conn->close();
?>
