<?php include_once '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Cars</h5>
                    <p class="card-text">10</p>
                    <a href="manage_cars.php" class="btn btn-primary">Manage Cars</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Bookings</h5>
                    <p class="card-text">5</p>
                    <a href="view_bookings.php" class="btn btn-primary">View Bookings</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Registered Users</h5>
                    <p class="card-text">20</p>
                    <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
