<?php
include_once '../includes/header.php';

// Include database connection
include_once '../includes/db.php';

// Fetch bookings data from the database
$sql = "SELECT b.id, u.username, c.model, b.start_date, b.end_date, b.total_price, b.status
        FROM bookings b
        INNER JOIN users u ON b.user_id = u.id
        INNER JOIN cars c ON b.car_id = c.id
        ORDER BY b.created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container mt-5">
    <h2>View Bookings</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Car Model</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking['id']; ?></td>
                        <td><?php echo $booking['username']; ?></td>
                        <td><?php echo $booking['model']; ?></td>
                        <td><?php echo $booking['start_date']; ?></td>
                        <td><?php echo $booking['end_date']; ?></td>
                        <td><?php echo $booking['total_price']; ?></td>
                        <td><?php echo $booking['status']; ?></td>
                        <td>
                            <a href="edit_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
