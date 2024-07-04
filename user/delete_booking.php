<?php
session_start();
include_once '../includes/auth.php';
include_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["booking_id"])) {
    $booking_id = intval($_GET["booking_id"]);
    $user_id = $_SESSION["user_id"];

    // Verify that the booking belongs to the user
    $sql = "SELECT * FROM bookings WHERE id = :booking_id AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        // Delete the booking
        $sql = "DELETE FROM bookings WHERE id = :booking_id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error.";
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
