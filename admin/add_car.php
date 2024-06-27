<?php
include_once '../includes/header.php';

// Initialize variables for form values
$model = $brand = $year = $price_per_day = $availability_status = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $model = htmlspecialchars($_POST['model']);
    $brand = htmlspecialchars($_POST['brand']);
    $year = htmlspecialchars($_POST['year']);
    $price_per_day = htmlspecialchars($_POST['price_per_day']);
    $availability_status = htmlspecialchars($_POST['availability_status']);

    // Validate input (basic example, customize as per your needs)
    if (empty($model)) {
        $errors['model'] = 'Model is required';
    }
    if (empty($brand)) {
        $errors['brand'] = 'Brand is required';
    }
    // Add more validation rules as needed (year, price, etc.)

    // If no errors, insert into database
    if (empty($errors)) {
        // Include database connection
        include_once '../includes/db.php';

        // SQL insert statement
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

            // Redirect after successful insertion (optional)
            header('Location: manage_cars.php?status=success');
            exit;
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5">
    <h2>Add New Car</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control <?php echo isset($errors['model']) ? 'is-invalid' : ''; ?>" id="model" name="model" value="<?php echo htmlspecialchars($model); ?>" required>
            <?php if (isset($errors['model'])): ?>
                <div class="invalid-feedback"><?php echo $errors['model']; ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" class="form-control <?php echo isset($errors['brand']) ? 'is-invalid' : ''; ?>" id="brand" name="brand" value="<?php echo htmlspecialchars($brand); ?>" required>
            <?php if (isset($errors['brand'])): ?>
                <div class="invalid-feedback"><?php echo $errors['brand']; ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>" required>
            <!-- Add validation as needed -->
        </div>
        <div class="mb-3">
            <label for="price_per_day" class="form-label">Price per Day</label>
            <input type="text" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo htmlspecialchars($price_per_day); ?>" required>
            <!-- Add validation as needed -->
        </div>
        <div class="mb-3">
            <label for="availability_status" class="form-label">Availability Status</label>
            <select class="form-select" id="availability_status" name="availability_status" required>
                <option value="available">Available</option>
                <option value="rented">Rented</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Car</button>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>
