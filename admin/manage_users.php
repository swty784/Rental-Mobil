<?php
include_once '../includes/header.php';
include_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['block_user'])) {
        $user_id = $_POST['user_id'];

        $sql_status = "SELECT status FROM users WHERE id = :id";
        $stmt_status = $conn->prepare($sql_status);
        $stmt_status->bindParam(':id', $user_id);
        $stmt_status->execute();
        $current_status = $stmt_status->fetchColumn();

        if ($current_status == 'active') {
            $new_status = 'blocked';
        } elseif ($current_status == 'blocked') {
            $new_status = 'active';
        }

        $sql_update = "UPDATE users SET status = :new_status WHERE id = :id";
        try {
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':new_status', $new_status);
            $stmt_update->bindParam(':id', $user_id);
            $stmt_update->execute();
            header('Location: manage_users.php?status=updated');
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$sql = "SELECT id, username, email, role, created_at, status FROM users ORDER BY created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container mt-5">
    <h2>Manage Users</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td><?php echo ucfirst($user['status']); ?></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="block_user" class="btn btn-<?php echo ($user['status'] == 'active') ? 'danger' : 'primary'; ?> btn-sm">
                                    <?php echo ($user['status'] == 'active') ? 'Block' : 'Unblock'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
