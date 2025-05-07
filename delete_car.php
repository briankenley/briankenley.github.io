<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = '';
$message_type = 'danger'; // Default to error

if ($car_id > 0) {
    // First, get the image path to delete the file later
    $stmt_select = $conn->prepare("SELECT image FROM cars WHERE id = ? AND user_id = ?");
    if ($stmt_select) {
        $stmt_select->bind_param("ii", $car_id, $user_id);
        $stmt_select->execute();
        $result_select = $stmt_select->get_result();

        if ($result_select->num_rows === 1) {
            $car = $result_select->fetch_assoc();
            $image_path = $car['image'];

            // Now, delete the car record using a prepared statement
            $stmt_delete = $conn->prepare("DELETE FROM cars WHERE id = ? AND user_id = ?");
            if ($stmt_delete) {
                $stmt_delete->bind_param("ii", $car_id, $user_id);

                if ($stmt_delete->execute()) {
                    // If deletion successful, try to delete the image file
                    if (!empty($image_path) && file_exists($image_path) && $image_path !== 'placeholder.png') {
                        if (unlink($image_path)) {
                            // Image deleted successfully
                            $message = "Mobil dan gambar terkait berhasil dihapus.";
                            $message_type = 'success';
                        } else {
                            // Image deletion failed, but DB record deleted
                            $message = "Mobil berhasil dihapus dari database, tetapi gagal menghapus file gambar.";
                            $message_type = 'warning';
                        }
                    } else {
                        // No image to delete or image doesn't exist
                        $message = "Mobil berhasil dihapus.";
                        $message_type = 'success';
                    }
                } else {
                    $message = "Gagal menghapus mobil: " . $stmt_delete->error;
                }
                $stmt_delete->close();
            } else {
                 $message = "Gagal menyiapkan query delete: " . $conn->error;
            }
        } else {
            // Car not found or doesn't belong to the user
            $message = "Mobil tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.";
        }
        $stmt_select->close();
    } else {
         $message = "Gagal menyiapkan query select: " . $conn->error;
    }
} else {
    $message = "ID mobil tidak valid.";
}

$conn->close();

// Store message in session to display on dashboard
$_SESSION['dashboard_message'] = $message;
$_SESSION['dashboard_message_type'] = $message_type;

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>
