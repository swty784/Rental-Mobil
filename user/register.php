<?php
include_once 'header.php';
include_once '../includes/db.php';

$username = $email = $password = $confirm_password = "";
$register_success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);

    $email = trim($_POST["email"]);

    $password = trim($_POST["password"]);
    
    $confirm_password = trim($_POST["confirm_password"]);

    if (!empty($username) && !empty($email) && !empty($password) && ($password == $confirm_password)) {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            if ($stmt->execute()) {
                $register_success = "Registrasi berhasil. Anda bisa melakukan <a href='login.php'>login disini</a>.";
            }
            unset($stmt);
        }
    }

    unset($conn);
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h2>Register</h2>
                </div>
                <div class="card-body">
                    <?php
                    if (!empty($register_success)) {
                        echo '<div class="alert alert-success">' . $register_success . '</div>';
                    }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username)) ? '' : 'invalid'; ?>" value="<?php echo $username; ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control <?php echo (!empty($email)) ? '' : 'invalid'; ?>" value="<?php echo $email; ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password)) ? '' : 'invalid'; ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password)) ? '' : 'invalid'; ?>">
                        </div>
                        <div class="form-group mb-3 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="Submit">
                        </div>
                        <p class="text-center">Sudah punya akun? <a href="login.php">Login disini</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
