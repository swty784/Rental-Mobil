<?php
include_once '../includes/header.php';
include_once '../includes/auth.php';
include_once '../includes/db.php';

// Fetch user bookings from the database
$user_id = $_SESSION["id"];
$sql = "SELECT b.id, c.model, c.brand, b.start_date, b.end_date, b.total_price, b.status
        FROM bookings b
        JOIN cars c ON b.car_id = c.id
        WHERE b.user_id = :user_id
        ORDER BY b.created_at DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $bookings = $stmt->fetchAll();
    unset($stmt);
} else {
    echo "Something went wrong. Please try again later.";
}

unset($conn);
?>

<div class="container mt-5">
    <h2>Your Bookings</h2>
    <p>Here you can see the details of your car bookings.</p>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Car</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                        <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                        <td><?php echo htmlspecialchars('$' . number_format($booking['total_price'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No bookings found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include_once '../includes/footer.php'; ?>
