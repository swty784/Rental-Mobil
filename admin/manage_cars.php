<?php
// Include necessary files
include_once '../includes/header.php';
include_once '../includes/auth.php'; // Ensure authentication
include_once '../includes/db.php'; // Database connection

// Fetch all cars from database
$stmt = $conn->query("SELECT * FROM cars");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Manage Cars</h2>
    <a href="add_car.php" class="btn btn-primary mb-3">Add New Car</a>

    <?php if (count($cars) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Brand</th>
                    <th>Year</th>
                    <th>Availability</th>
                    <th>Price per Day</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($car['model']); ?></td>
                        <td><?php echo htmlspecialchars($car['brand']); ?></td>
                        <td><?php echo htmlspecialchars($car['year']); ?></td>
                        <td><?php echo htmlspecialchars($car['availability_status']); ?></td>
                        <td><?php echo htmlspecialchars($car['price_per_day']); ?></td>
                        <td>
                            <a href="edit_car.php?car_id=<?php echo $car['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete_car.php?id=<?php echo $car['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this car?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No cars found.</p>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
