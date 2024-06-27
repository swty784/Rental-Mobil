<?php
include_once '../includes/header.php';
include_once '../includes/auth.php';
include_once '../includes/db.php';

// Initialize variables
$car_id = $start_date = $end_date = $total_price = "";
$car_id_err = $start_date_err = $end_date_err = $booking_err = $booking_success = "";

// Fetch available cars from the database
$sql = "SELECT id, model, brand, price_per_day FROM cars WHERE availability_status = 'available'";
$cars = $conn->query($sql)->fetchAll();

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate car
    if (empty(trim($_POST["car_id"]))) {
        $car_id_err = "Please select a car.";
    } else {
        $car_id = trim($_POST["car_id"]);
    }

    // Validate start date
    if (empty(trim($_POST["start_date"]))) {
        $start_date_err = "Please select a start date.";
    } else {
        $start_date = trim($_POST["start_date"]);
    }

    // Validate end date
    if (empty(trim($_POST["end_date"]))) {
        $end_date_err = "Please select an end date.";
    } else {
        $end_date = trim($_POST["end_date"]);
    }

    // Calculate total price
    if (empty($car_id_err) && empty($start_date_err) && empty($end_date_err)) {
        $sql = "SELECT price_per_day FROM cars WHERE id = :car_id";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":car_id", $car_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($row = $stmt->fetch()) {
                    $price_per_day = $row["price_per_day"];
                    $start = new DateTime($start_date);
                    $end = new DateTime($end_date);
                    $interval = $start->diff($end);
                    $total_price = $price_per_day * $interval->days;
                }
            }
            unset($stmt);
        }
    }

    // Check input errors before inserting in database
    if (empty($car_id_err) && empty($start_date_err) && empty($end_date_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price) VALUES (:user_id, :car_id, :start_date, :end_date, :total_price)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":user_id", $param_user_id, PDO::PARAM_INT);
            $stmt->bindParam(":car_id", $param_car_id, PDO::PARAM_INT);
            $stmt->bindParam(":start_date", $param_start_date, PDO::PARAM_STR);
            $stmt->bindParam(":end_date", $param_end_date, PDO::PARAM_STR);
            $stmt->bindParam(":total_price", $param_total_price, PDO::PARAM_STR);

            // Set parameters
            $param_user_id = $_SESSION["user_id"];
            $param_car_id = $car_id;
            $param_start_date = $start_date;
            $param_end_date = $end_date;
            $param_total_price = $total_price;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $booking_success = "Car booked successfully.";
            } else {
                $booking_err = "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($conn);
}
?>

<div class="container mt-5">
    <h2>Book a Car</h2>
    <p>Fill in the details below to book a car.</p>
    <?php
    if (!empty($booking_success)) {
        echo '<div class="alert alert-success">' . $booking_success . '</div>';
    }
    if (!empty($booking_err)) {
        echo '<div class="alert alert-danger">' . $booking_err . '</div>';
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Car</label>
            <select name="car_id" class="form-control <?php echo (!empty($car_id_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select a car</option>
                <?php foreach ($cars as $car): ?>
                    <option value="<?php echo $car['id']; ?>" <?php echo ($car['id'] == $car_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' - $' . $car['price_per_day'] . ' per day'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"><?php echo $car_id_err; ?></span>
        </div>
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control <?php echo (!empty($start_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $start_date; ?>">
            <span class="invalid-feedback"><?php echo $start_date_err; ?></span>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control <?php echo (!empty($end_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $end_date; ?>">
            <span class="invalid-feedback"><?php echo $end_date_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Book Now">
        </div>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>
