<?php include_once '../includes/header.php'; ?>
<?php include_once '../includes/db.php'; 

$car_id = $_GET['car_id'] ?? '';
$model = $brand = $year = $price_per_day = $availability_status = '';
$errors = [];

if (!empty($car_id)) {
    $sql = "SELECT * FROM cars WHERE id = :car_id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':car_id', $car_id);
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($car) {
            $model = $car['model'];
            $brand = $car['brand'];
            $year = $car['year'];
            $price_per_day = $car['price_per_day'];
            $availability_status = $car['availability_status'];
        } else {
            echo "Tidak menemukan mobil.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = htmlspecialchars($_POST['model']);
    $brand = htmlspecialchars($_POST['brand']);
    $year = htmlspecialchars($_POST['year']);
    $price_per_day = floatval(str_replace(',', '', $_POST['price_per_day']));
    $availability_status = htmlspecialchars($_POST['availability_status']);

    if (empty($model)) {
        $errors['model'] = 'Masukkan Mobil';
    }
    if (empty($brand)) {
        $errors['brand'] = 'Masukkan Brand';
    }

    if (empty($errors)) {
        $sql = "UPDATE cars SET 
                model = :model,
                brand = :brand,
                year = :year,
                price_per_day = :price_per_day,
                availability_status = :availability_status
                WHERE id = :car_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':model', $model);
            $stmt->bindParam(':brand', $brand);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':price_per_day', $price_per_day);
            $stmt->bindParam(':availability_status', $availability_status);
            $stmt->bindParam(':car_id', $car_id);
            $stmt->execute();

            header('Location: manage_cars.php?status=success');
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title mb-0">Edit Car</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?car_id=' . $car_id); ?>">
                <div class="mb-3 row">
                    <label for="model" class="col-sm-3 col-form-label">Model</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control <?php echo isset($errors['model']) ? 'is-invalid' : ''; ?>" id="model" name="model" value="<?php echo htmlspecialchars($model); ?>" required>
                        <?php if (isset($errors['model'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['model']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="brand" class="col-sm-3 col-form-label">Brand</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control <?php echo isset($errors['brand']) ? 'is-invalid' : ''; ?>" id="brand" name="brand" value="<?php echo htmlspecialchars($brand); ?>" required>
                        <?php if (isset($errors['brand'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['brand']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="year" class="col-sm-3 col-form-label">Tahun</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>" required>
                        <!-- Add validation as needed -->
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="price_per_day" class="col-sm-3 col-form-label">Harga sewa</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo number_format($price_per_day, 0, ',', '.'); ?>" required>
                        <!-- Add validation as needed -->
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="availability_status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <select class="form-select <?php echo isset($errors['availability_status']) ? 'is-invalid' : ''; ?>" id="availability_status" name="availability_status" required>
                            <option value="available" <?php echo ($availability_status === 'available') ? 'selected' : ''; ?>>Available</option>
                            <option value="rented" <?php echo ($availability_status === 'rented') ? 'selected' : ''; ?>>Rented</option>
                            <option value="maintenance" <?php echo ($availability_status === 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        </select>
                        <?php if (isset($errors['availability_status'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['availability_status']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
