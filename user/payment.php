<?php
include_once '../includes/header.php';
include_once '../includes/auth.php'; 
include_once '../includes/db.php';

$payment_id = $new_status = "";
$payment_id_err = $status_err = $update_err = $update_success = "";

// Fetch payment details if payment_id is set
if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch the current status of the payment
    $sql = "SELECT payment_status FROM payments WHERE id = :payment_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
    $stmt->execute();
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($payment) {
        $current_status = $payment['payment_status'];
    } else {
        $update_err = "Payment not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = trim($_POST["payment_id"]) ?: $payment_id_err = "Payment ID is required.";
    $new_status = trim($_POST["new_status"]) ?: $status_err = "Select a new status.";

    if (!$payment_id_err && !$status_err) {
        // Update payment status in the database
        $sql = "UPDATE payments SET payment_status = :new_status WHERE id = :payment_id";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":new_status", $new_status, PDO::PARAM_STR);
            $stmt->bindParam(":payment_id", $payment_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $update_success = "Payment status updated successfully.";
            } else {
                $update_err = "Error updating payment status.";
            }
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Update Payment Status</h2>
                    <?php if ($update_success): ?>
                        <div class="alert alert-success text-center"><?= $update_success ?></div>
                    <?php endif; ?>
                    <?php if ($update_err): ?>
                        <div class="alert alert-danger text-center"><?= $update_err ?></div>
                    <?php endif; ?>
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                        <div class="mb-3">
                            <label for="payment_id" class="form-label">Payment ID</label>
                            <input type="text" id="payment_id" name="payment_id" class="form-control <?= $payment_id_err ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($payment_id) ?>">
                            <?php if ($payment_id_err): ?><div class="invalid-feedback"><?= $payment_id_err ?></div><?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="new_status" class="form-label">New Status</label>
                            <select id="new_status" name="new_status" class="form-select <?= $status_err ? 'is-invalid' : ''; ?>">
                                <option value="">Select Status</option>
                                <option value="pending" <?= $new_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?= $new_status == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="failed" <?= $new_status == 'failed' ? 'selected' : ''; ?>>Failed</option>
                            </select>
                            <?php if ($status_err): ?><div class="invalid-feedback"><?= $status_err ?></div><?php endif; ?>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
