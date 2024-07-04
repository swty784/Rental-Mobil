<?php
include_once '../includes/header.php';

$model = $brand = $year = $price_per_day = $availability_status = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = htmlspecialchars($_POST['model']);
    $brand = htmlspecialchars($_POST['brand']);
    $year = htmlspecialchars($_POST['year']);
    // Convert IDR back to float for database storage
    $price_per_day = floatval(str_replace(',', '', $_POST['price_per_day'])); 
    $availability_status = htmlspecialchars($_POST['availability_status']);

    if (empty($model)) {
        $errors['model'] = 'Masukkan Mobil';
    }
    if (empty($brand)) {
        $errors['brand'] = 'Masukkan Brand';
    }

    if (empty($errors)) {
        include_once '../includes/db.php';

        $sql = "INSERT INTO cars (model, brand, year, availability_status, price_per_day, created_at)
                VALUES (:model, :brand, :year, :availability_status, :price_per_day, NOW())";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':model', $model);
            $stmt->bindParam(':brand', $brand);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':availability_status', $availability_status);
            $stmt->bindParam(':price_per_day', $price_per_day);
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
            <h2 class="card-title mb-0">Tambah Mobil Baru</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
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
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="price_per_day" class="col-sm-3 col-form-label">Harga Sewa</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo htmlspecialchars($price_per_day); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="availability_status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <select class="form-select <?php echo isset($errors['availability_status']) ? 'is-invalid' : ''; ?>" id="availability_status" name="availability_status" required>
                            <option value="available">Available</option>
                            <option value="rented">Rented</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <?php if (isset($errors['availability_status'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['availability_status']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">Tambah Mobil</button>
                        <a href="manage_cars.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
