<?php
// Database checker script
include 'db.php';

echo "<h1>Database Structure Checker</h1>";
echo "<p>This script checks if all required tables and columns exist in the database.</p>";

// Function to check if a table exists
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result->num_rows > 0;
}

// Function to check if a column exists in a table
function columnExists($conn, $tableName, $columnName) {
    $result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
    return $result->num_rows > 0;
}

// Check tables
$requiredTables = ['users', 'cars', 'contacts', 'purchases'];
$tablesStatus = [];

foreach ($requiredTables as $table) {
    $tablesStatus[$table] = tableExists($conn, $table);
}

// Check specific columns that might cause issues
$requiredColumns = [
    'users' => ['id', 'username', 'password', 'role', 'created_at'],
    'cars' => ['id', 'user_id', 'make', 'model', 'year', 'price', 'mileage', 'description', 'image', 'created_at'],
    'contacts' => ['id', 'name', 'email', 'message', 'created_at'],
    'purchases' => ['id', 'car', 'name', 'address', 'payment_method', 'comment', 'rating', 'username']
];

$columnsStatus = [];

foreach ($requiredColumns as $table => $columns) {
    if ($tablesStatus[$table]) {
        $columnsStatus[$table] = [];
        foreach ($columns as $column) {
            $columnsStatus[$table][$column] = columnExists($conn, $table, $column);
        }
    }
}

// Display results
echo "<h2>Tables Status:</h2>";
echo "<ul>";
foreach ($tablesStatus as $table => $exists) {
    $status = $exists ? "✅ Exists" : "❌ Missing";
    $color = $exists ? "green" : "red";
    echo "<li style='color: $color;'><strong>$table</strong>: $status</li>";
}
echo "</ul>";

echo "<h2>Columns Status:</h2>";
foreach ($columnsStatus as $table => $columns) {
    echo "<h3>Table: $table</h3>";
    echo "<ul>";
    foreach ($columns as $column => $exists) {
        $status = $exists ? "✅ Exists" : "❌ Missing";
        $color = $exists ? "green" : "red";
        echo "<li style='color: $color;'><strong>$column</strong>: $status</li>";
    }
    echo "</ul>";
}

// Check for admin user
echo "<h2>Admin User Status:</h2>";
$adminResult = $conn->query("SELECT id, username, role FROM users WHERE username = 'admin' AND role = 'admin'");
if ($adminResult->num_rows > 0) {
    $adminUser = $adminResult->fetch_assoc();
    echo "<p style='color: green;'>✅ Admin user exists (ID: {$adminUser['id']})</p>";
} else {
    echo "<p style='color: red;'>❌ Admin user does not exist. Run create_admin.php to create it.</p>";
}

$conn->close();
?>

<p><a href="mobilkupedia.php">Back to Home</a></p>
