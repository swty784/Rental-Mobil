<?php
include_once '../includes/header.php';
include_once '../includes/auth.php'; 
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];

$sqlBookings = "SELECT b.id, c.model, b.start_date, b.end_date, b.status
                FROM bookings b
                INNER JOIN cars c ON b.car_id = c.id
                WHERE b.user_id = :userId
                ORDER BY b.created_at DESC";
$stmtBookings = $conn->prepare($sqlBookings);
$stmtBookings->bindParam(':userId', $user_id, PDO::PARAM_INT);
$stmtBookings->execute();
$bookings = $stmtBookings->fetchAll(PDO::FETCH_ASSOC);

$sqlCars = "SELECT id, model, brand, year, price_per_day AS harga_sewa
            FROM cars
            WHERE availability_status = 'available'
            ORDER BY created_at DESC";
$stmtCars = $conn->prepare($sqlCars);
$stmtCars->execute();
$cars = $stmtCars->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">List Booking</h2>
    
    <div class="row">
        <div class="col-md-12 mb-5">
            <?php if (count($bookings) > 0): ?>
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Mobil</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td><?php echo $booking['model']; ?></td>
                                <td><?php echo $booking['start_date']; ?></td>
                                <td><?php echo $booking['end_date']; ?></td>
                                <td><?php echo $booking['status']; ?></td>
                                <td>
                                    <a href="update_booking.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-warning">Update</a>
                                    <a href="delete_booking.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger">Cancel</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    Tidak ada data booking.
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <h3 class="text-center mb-4">Pilihan Mobil</h3>
            <div class="row">
                <?php foreach ($cars as $car): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo $car['model']; ?></h5>
                                <p class="card-text"><?php echo $car['brand'] . ' (' . $car['year'] . ')'; ?></p>
                                <p class="card-text">Harga sewa: Rp <?php echo number_format($car['harga_sewa'], 0, ',', '.'); ?></p>
                                <a href="book_car.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary mt-auto">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
