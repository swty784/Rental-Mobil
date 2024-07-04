<?php
include_once '../includes/db.php';

// Function to update booking status
function updateBookingStatus($booking_id, $new_status, $conn) {
    $sql = "UPDATE bookings SET status = :status WHERE id = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $new_status);
        $stmt->bindParam(':id', $booking_id);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Start output buffering
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];

    if (updateBookingStatus($booking_id, $new_status, $conn)) {
        // Redirect or show success message
        header('Location: view_bookings.php?status=success');
        exit;
    } else {
        // Handle error
        echo '<script>alert("Failed to update status.");</script>';
    }
}

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

include_once '../includes/header.php';
?>

<div class="container mt-5">
    <h2>View Bookings</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Mobil</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Harga Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
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
                        <td><?php echo number_format($booking['total_price'], 0, ',', '.'); ?></td>
                        <td><?php echo ucfirst($booking['status']); ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $booking['id']; ?>">View</button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="viewModal<?php echo $booking['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $booking['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel<?php echo $booking['id']; ?>">Detail Booking</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <div class="modal-body">
                                        <p><strong>User:</strong> <?php echo $booking['username']; ?></p>
                                        <p><strong>Mobil:</strong> <?php echo $booking['model']; ?></p>
                                        <p><strong>Start Date:</strong> <?php echo $booking['start_date']; ?></p>
                                        <p><strong>End Date:</strong> <?php echo $booking['end_date']; ?></p>
                                        <p><strong>Harga Total:</strong> Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></p>
                                        <div class="mb-3">
                                            <label for="statusSelect<?php echo $booking['id']; ?>" class="form-label"><strong>Status:</strong></label>
                                            <select class="form-select" id="statusSelect<?php echo $booking['id']; ?>" name="status">
                                                <option value="pending" <?php if ($booking['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                                <option value="confirmed" <?php if ($booking['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                                <option value="canceled" <?php if ($booking['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
                                                <option value="completed" <?php if ($booking['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" name="update_status" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-21I+9kTVheLb5Acna3D/5JwX6l7YCUOa+CeWP5YdIdHX6G+PGw8nHtUmLRtctS+JnBwRChV1TiXBj/yoBrS+Kg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js" integrity="sha512-VK2zcvntEufaimc+efOYi622VN5ZacdnufnmX7zIhCPmjhKnOi9ZDMtg1/ug5l183f19gG1/cBstPO4D8N/Img==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php include_once '../includes/footer.php'; ?>

<?php
// End output buffering and send the output
ob_end_flush();
?>
