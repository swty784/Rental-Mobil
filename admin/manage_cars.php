<?php
include_once '../includes/header.php';
include_once '../includes/auth.php';
include_once '../includes/db.php';

// Fetch cars from the database
$stmt = $conn->query("SELECT * FROM cars");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Mengelola Data Mobil</h2>
        <a href="add_car.php" class="btn btn-primary">Tambah</a>
    </div>

    <?php if (count($cars) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Model</th>
                        <th>Brand</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Harga Perhari (IDR)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($car['model']); ?></td>
                            <td><?php echo htmlspecialchars($car['brand']); ?></td>
                            <td><?php echo htmlspecialchars($car['year']); ?></td>
                            <td><?php echo htmlspecialchars($car['availability_status']); ?></td>
                            <td>Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="edit_car.php?car_id=<?php echo $car['id']; ?>" class="btn btn-sm btn-warning me-2">Edit</a>
                                <a href="delete_car.php?id=<?php echo $car['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            Tidak ada data mobil.
        </div>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
