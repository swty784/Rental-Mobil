<?php
// Include necessary files
include_once '../includes/auth.php'; // Ensure authentication
include_once '../includes/db.php'; // Database connection

// Check if car_id is provided in the URL
$car_id = $_GET['id'] ?? '';

// Ensure car_id is not empty
if (!empty($car_id)) {
    try {
        // Check if there are bookings associated with this car
        $stmt_check_bookings = $conn->prepare("SELECT id FROM bookings WHERE car_id = :car_id");
        $stmt_check_bookings->bindParam(':car_id', $car_id);
        $stmt_check_bookings->execute();
        $has_bookings = $stmt_check_bookings->fetchColumn();

        if ($has_bookings) {
            // Handle case where there are bookings associated with this car
            // Redirect back with an error or warning message
            header('Location: manage_cars.php?status=has_bookings');
            exit;
        } else {
            // No bookings associated, proceed with deletion
            $sql = "DELETE FROM cars WHERE id = :car_id";

            // Prepare and execute the SQL statement
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':car_id', $car_id);
            $stmt->execute();

            // Redirect after successful deletion
            header('Location: manage_cars.php?status=deleted');
            exit;
        }
    } catch (PDOException $e) {
        // Handle database error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect to manage_cars.php if car_id is not provided
    header('Location: manage_cars.php');
    exit;
}
?>
