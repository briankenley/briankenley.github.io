<?php
include 'db.php'; // Include your database connection script

$admin_username = 'admin';
$admin_password = 'admin'; // Plain text password
$admin_role = 'admin';

// Check if admin user already exists (UNSAFE query)
$check_sql = "SELECT id FROM users WHERE username = '$admin_username'";
$check_result = $conn->query($check_sql);

if ($check_result && $check_result->num_rows > 0) {
    echo "Admin user '{$admin_username}' already exists.\n";
} else {
    // Insert the admin user with plain text password (UNSAFE query)
    $sql = "INSERT INTO users (username, password, role) VALUES ('$admin_username', '$admin_password', '$admin_role')";

    if ($conn->query($sql) === TRUE) {
        echo "Admin user '{$admin_username}' created successfully.\n";
    } else {
        echo "Error creating admin user '{$admin_username}': " . $conn->error . "\n";
    }
}

// Close the connection
$conn->close();
?>
