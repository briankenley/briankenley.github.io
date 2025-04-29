<?php
include 'db.php'; // Include your database connection script

$admin_username = 'admin';
$admin_password = 'admin'; // Plain text password
$admin_role = 'admin';

// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

if ($hashed_password === false) {
    die("Error hashing password.\n");
}

// Check if admin user already exists
$check_sql = "SELECT id FROM users WHERE username = ?";
$check_stmt = $conn->prepare($check_sql);

if ($check_stmt === false) {
    die("Error preparing check statement: " . $conn->error . "\n");
}

$check_stmt->bind_param("s", $admin_username);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "Admin user '{$admin_username}' already exists.\n";
    $check_stmt->close();
    $conn->close();
    exit(); // Exit if admin already exists
}
$check_stmt->close();


// Prepare the SQL statement to insert the admin user
$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing insert statement: " . $conn->error . "\n");
}

// Bind parameters and execute
$stmt->bind_param("sss", $admin_username, $hashed_password, $admin_role);

if ($stmt->execute()) {
    echo "Admin user '{$admin_username}' created successfully.\n";
} else {
    echo "Error creating admin user '{$admin_username}': " . $stmt->error . "\n";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
