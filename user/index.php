<?php
include_once '../includes/header.php';
include_once '../includes/auth.php'; 
include_once '../includes/db.php';

// Fetch user-specific data
$user_id = $_SESSION['user_id'];

// Fetch user bookings
$sqlBookings = "SELECT b.id, c.model, b.start_date, b.end_date, b.status
                FROM bookings b
                INNER JOIN cars c ON b.car_id = c.id
                WHERE b.user_id = :userId
                ORDER BY b.created_at DESC";
$stmtBookings = $conn->prepare($sqlBookings);
$stmtBookings->bindParam(':userId', $user_id, PDO::PARAM_INT);
$stmtBookings->execute();
$bookings = $stmtBookings->fetchAll(PDO::FETCH_ASSOC);

// Fetch available cars
$sqlCars = "SELECT id, model, brand, year, price_per_day
            FROM cars
            WHERE availability_status = 'available'
            ORDER BY created_at DESC";
$stmtCars = $conn->prepare($sqlCars);
$stmtCars->execute();
$cars = $stmtCars->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>User Dashboard</h2>
    
    <div class="row">
        <div class="col-md-6">
            <h3>My Bookings</h3>
            <?php if (count($bookings) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Car Model</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td><?php echo $booking['model']; ?></td>
                                <td><?php echo $booking['start_date']; ?></td>
                                <td><?php echo $booking['end_date']; ?></td>
                                <td><?php echo ucfirst($booking['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no bookings.</p>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <h3>Available Cars</h3>
            <div class="row">
                <?php foreach ($cars as $car): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $car['model']; ?></h5>
                                <p class="card-text"><?php echo $car['brand'] . ' (' . $car['year'] . ')'; ?></p>
                                <p class="card-text">Price per day: $<?php echo $car['price_per_day']; ?></p>
                                <a href="book_car.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
