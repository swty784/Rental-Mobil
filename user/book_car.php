<?php
include_once '../includes/header.php';
include_once '../includes/auth.php';
include_once '../includes/db.php';

$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : null;
$start_date = $end_date = $total_price = "";
$car_id_err = $start_date_err = $end_date_err = $booking_err = $booking_success = "";

// Fetch the selected car
$selected_car = null;
if ($car_id) {
    $sql = "SELECT id, model, brand, price_per_day FROM cars WHERE id = :car_id AND availability_status = 'available'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":car_id", $car_id, PDO::PARAM_INT);
    $stmt->execute();
    $selected_car = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all available cars
$sql = "SELECT id, model, brand, price_per_day FROM cars WHERE availability_status = 'available'";
$cars = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = trim($_POST["car_id"]) ?: $car_id_err = "Pilih mobil.";
    $start_date = trim($_POST["start_date"]) ?: $start_date_err = "Pilih tanggal mulai sewa.";
    $end_date = trim($_POST["end_date"]) ?: $end_date_err = "Pilih tanggal akhir sewa.";

    if (!$car_id_err && !$start_date_err && !$end_date_err) {
        $sql = "SELECT price_per_day FROM cars WHERE id = :car_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":car_id", $car_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $price_per_day = $row["price_per_day"];
            $interval = (new DateTime($start_date))->diff(new DateTime($end_date));
            $total_price = $price_per_day * $interval->days;

            $sql = "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price) VALUES (:user_id, :car_id, :start_date, :end_date, :total_price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
            $stmt->bindParam(":car_id", $car_id, PDO::PARAM_INT);
            $stmt->bindParam(":start_date", $start_date, PDO::PARAM_STR);
            $stmt->bindParam(":end_date", $end_date, PDO::PARAM_STR);
            $stmt->bindParam(":total_price", $total_price, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $booking_success = "Booking berhasil.";
            } else {
                $booking_err = "Terjadi error.";
            }
        } else {
            $car_id_err = "Mobil tidak ditemukan.";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Booking Mobil</h2>
                    <?php if ($booking_success): ?>
                        <div class="alert alert-success text-center"><?= $booking_success ?></div>
                    <?php endif; ?>
                    <?php if ($booking_err): ?>
                        <div class="alert alert-danger text-center"><?= $booking_err ?></div>
                    <?php endif; ?>
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                        <input type="hidden" name="car_id" value="<?= $selected_car['id'] ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control <?= $start_date_err ? 'is-invalid' : ''; ?>" value="<?= $start_date ?>">
                                <?php if ($start_date_err): ?><div class="invalid-feedback"><?= $start_date_err ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control <?= $end_date_err ? 'is-invalid' : ''; ?>" value="<?= $end_date ?>">
                                <?php if ($end_date_err): ?><div class="invalid-feedback"><?= $end_date_err ?></div><?php endif; ?>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
