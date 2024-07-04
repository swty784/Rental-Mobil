<?php
// Include necessary files
include_once '../includes/auth.php'; 
include_once '../includes/db.php';

$car_id = $_GET['id'] ?? '';

if (!empty($car_id)) {
    try {

        $stmt_check_bookings = $conn->prepare("SELECT id FROM bookings WHERE car_id = :car_id");
        $stmt_check_bookings->bindParam(':car_id', $car_id);
        $stmt_check_bookings->execute();
        $has_bookings = $stmt_check_bookings->fetchColumn();

        if ($has_bookings) {
            header('Location: manage_cars.php?status=has_bookings');
            exit;
        } else {

            $sql = "DELETE FROM cars WHERE id = :car_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':car_id', $car_id);
            $stmt->execute();

            header('Location: manage_cars.php?status=deleted');
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: manage_cars.php');
    exit;
}
?>
