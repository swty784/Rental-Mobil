<?php
include_once '../includes/header.php';
include_once '../includes/db.php';

$email = $password = "";
$login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT id, username, email, password, role, status FROM users WHERE email = :email";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = $email;

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        $role = $row["role"];
                        $status = $row["status"];

                        if ($status === 'blocked') {
                            $login_err = "Akunmu telah diblokir.";
                        } elseif (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;

                            if ($role == 'admin') {
                                header("location: ../admin/index.php");
                            } else {
                                header("location: ../user/index.php");
                            }
                            exit();
                        } else {
                            $login_err = "Invalid email & password.";
                        }
                    }
                } else {
                    $login_err = "Invalid email & password.";
                }
            } else {
                $login_err = "Error.";
            }
            unset($stmt);
        }
    } else {
        $login_err = "Isi field.";
    }
    unset($conn);
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h2>Login</h2>
                </div>
                <div class="card-body">
                    <?php
                    if (!empty($login_err)) {
                        echo '<div class="alert alert-danger">' . $login_err . '</div>';
                    }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'invalid' : ''; ?>" value="<?php echo $email; ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'invalid' : ''; ?>">
                        </div>
                        <div class="form-group mb-3 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="Login">
                        </div>
                        <p class="text-center">Belum mempunyai akun? <a href="register.php">Daftar sekarang</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
