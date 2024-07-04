<?php
include_once '../includes/header.php';
include_once '../includes/auth.php';
include_once '../includes/db.php';

$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : null;
$start_date = $end_date = $booking_err = $booking_success = "";

// Fetch the booking details
$booking = null;
if ($booking_id) {
    $sql = "SELECT * FROM bookings WHERE id = :booking_id AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$booking) {
    header("Location: user_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = trim($_POST["start_date"]);
    $end_date = trim($_POST["end_date"]);

    if ($start_date && $end_date) {
        $sql = "UPDATE bookings SET start_date = :start_date, end_date = :end_date WHERE id = :booking_id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":start_date", $start_date, PDO::PARAM_STR);
        $stmt->bindParam(":end_date", $end_date, PDO::PARAM_STR);
        $stmt->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $booking_success = "Booking updated successfully.";
            // Update the booking array to reflect the changes
            $booking['start_date'] = $start_date;
            $booking['end_date'] = $end_date;
        } else {
            $booking_err = "Error updating booking.";
        }
    } else {
        $booking_err = "Please provide both start and end dates.";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Update Booking</h2>
                    <?php if ($booking_success): ?>
                        <div class="alert alert-success text-center"><?= $booking_success ?></div>
                    <?php endif; ?>
                    <?php if ($booking_err): ?>
                        <div class="alert alert-danger text-center"><?= $booking_err ?></div>
                    <?php endif; ?>
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . '?booking_id=' . $booking_id ?>" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($booking['start_date']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($booking['end_date']) ?>">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
