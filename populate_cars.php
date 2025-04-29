<?php
include 'db.php'; // Include your database connection script

// Define car data array
$cars_data = [
    [
        'user_id' => 1, // Assuming user ID 1 exists
        'make' => 'Toyota',
        'model' => 'Avanza',
        'year' => 2022,
        'price' => 250000000,
        'mileage' => 15000,
        'description' => 'Mobil keluarga populer, irit bahan bakar.',
        'image' => 'avanza.jpg'
    ],
    [
        'user_id' => 1,
        'make' => 'Honda',
        'model' => 'Jazz RS',
        'year' => 2021,
        'price' => 280000000,
        'mileage' => 22000,
        'description' => 'Hatchback sporty dan lincah, cocok untuk perkotaan.',
        'image' => 'jazz.jpg'
    ],
    [
        'user_id' => 1,
        'make' => 'Mitsubishi',
        'model' => 'Xpander Cross',
        'year' => 2023,
        'price' => 310000000,
        'mileage' => 8000,
        'description' => 'MPV Crossover tangguh dengan desain modern.',
        'image' => 'xpander.png'
    ],
    [
        'user_id' => 1,
        'make' => 'Nissan',
        'model' => 'X-Trail',
        'year' => 2020,
        'price' => 350000000,
        'mileage' => 35000,
        'description' => 'SUV nyaman dengan fitur lengkap, cocok untuk petualangan.',
        'image' => 'xtrail.jpg'
    ]
];

// Prepare the SQL statement once
$sql = "INSERT INTO cars (user_id, make, model, year, price, mileage, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters and execute for each car
$inserted_count = 0;
$errors = [];

foreach ($cars_data as $car) {
    // Check if car already exists (optional, based on make/model/year for simplicity)
    $check_sql = "SELECT id FROM cars WHERE make = ? AND model = ? AND year = ?";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt) {
        $check_stmt->bind_param("ssi", $car['make'], $car['model'], $car['year']);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $exists = $check_result->num_rows > 0;
        $check_stmt->close();

        if ($exists) {
            echo "Skipping: {$car['make']} {$car['model']} ({$car['year']}) already exists.\n";
            continue; // Skip insertion if it already exists
        }
    } else {
        echo "Warning: Could not prepare check statement: " . $conn->error . "\n";
        // Continue insertion attempt even if check fails
    }


    // Bind parameters for insertion
    $stmt->bind_param(
        "issidiss", // i: integer, s: string, d: double
        $car['user_id'],
        $car['make'],
        $car['model'],
        $car['year'],
        $car['price'],
        $car['mileage'],
        $car['description'],
        $car['image']
    );

    // Execute the statement
    if ($stmt->execute()) {
        $inserted_count++;
        echo "Successfully inserted: {$car['make']} {$car['model']}\n";
    } else {
        $errors[] = "Error inserting {$car['make']} {$car['model']}: " . $stmt->error;
        echo "Error inserting {$car['make']} {$car['model']}: " . $stmt->error . "\n";
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();

echo "\n--------------------\n";
echo "Script finished.\n";
echo "Total cars inserted: " . $inserted_count . "\n";
if (!empty($errors)) {
    echo "Errors encountered:\n";
    foreach ($errors as $error) {
        echo "- " . $error . "\n";
    }
}
echo "--------------------\n";

?>
