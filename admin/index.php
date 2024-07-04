<?php 
include_once 'header.php'; 
include_once '../includes/db.php';

$sqlTotalCars = "SELECT COUNT(*) AS total_cars FROM cars";
$stmtTotalCars = $conn->prepare($sqlTotalCars);
$stmtTotalCars->execute();
$totalCars = $stmtTotalCars->fetch(PDO::FETCH_ASSOC)['total_cars'];

$sqlActiveBookings = "SELECT COUNT(*) AS active_bookings FROM bookings";
$stmtActiveBookings = $conn->prepare($sqlActiveBookings);
$stmtActiveBookings->execute();
$activeBookings = $stmtActiveBookings->fetch(PDO::FETCH_ASSOC)['active_bookings'];

$sqlRegisteredUsers = "SELECT COUNT(*) AS registered_users FROM users";
$stmtRegisteredUsers = $conn->prepare($sqlRegisteredUsers);
$stmtRegisteredUsers->execute();
$registeredUsers = $stmtRegisteredUsers->fetch(PDO::FETCH_ASSOC)['registered_users'];
?>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">List Mobil</h5>
                    <p class="card-text display-4"><?php echo $totalCars; ?></p>
                    <a href="manage_cars.php" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">List Booking</h5>
                    <p class="card-text display-4"><?php echo $activeBookings; ?></p>
                    <a href="view_bookings.php" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">List User</h5>
                    <p class="card-text display-4"><?php echo $registeredUsers; ?></p>
                    <a href="manage_users.php" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
