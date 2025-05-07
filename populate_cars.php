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

$inserted_count = 0;
$error_count = 0;

echo "Attempting to insert car data...\n";

foreach ($cars_data as $car) {
    // Directly build and execute INSERT query (UNSAFE)
    // Note: Assumes values are safe for direct insertion as per simplification request
    $user_id = $car['user_id'];
    $make = $car['make']; // No escaping
    $model = $car['model']; // No escaping
    $year = $car['year'];
    $price = $car['price'];
    $mileage = $car['mileage'];
    $description = $car['description']; // No escaping
    $image = $car['image']; // No escaping

    $sql = "INSERT INTO cars (user_id, make, model, year, price, mileage, description, image)
            VALUES ('$user_id', '$make', '$model', '$year', '$price', '$mileage', '$description', '$image')";

    if ($conn->query($sql) === TRUE) {
        $inserted_count++;
        echo "Successfully inserted: {$car['make']} {$car['model']}\n";
    } else {
        $error_count++;
        echo "Error inserting {$car['make']} {$car['model']}: " . $conn->error . "\n";
    }
}

// Close the connection
$conn->close();

echo "\n--------------------\n";
echo "Script finished.\n";
echo "Total cars inserted: " . $inserted_count . "\n";
echo "Total errors: " . $error_count . "\n";
echo "--------------------\n";

?>
