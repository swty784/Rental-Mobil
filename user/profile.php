<?php
include_once '../includes/header.php';
include_once '../includes/auth.php';
include_once '../includes/db.php';

// Initialize variables
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = $update_success = "";

// Get the logged-in user's ID from the session
$user_id = $_SESSION["id"];

// Fetch user information from the database
$sql = "SELECT username, email FROM users WHERE id = :id";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        if ($row = $stmt->fetch()) {
            $username = $row["username"];
            $email = $row["email"];
        }
    }
    unset($stmt);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (!empty(trim($_POST["password"]))) {
        if (strlen(trim($_POST["password"])) < 6) {
            $password_err = "Password must have at least 6 characters.";
        } else {
            $password = trim($_POST["password"]);
        }
    }

    // Validate confirm password
    if (!empty(trim($_POST["confirm_password"]))) {
        if (empty($password_err) && ($password != trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Password did not match.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
        }
    }

    // Check input errors before updating in database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET username = :username, email = :email";
        if (!empty($password)) {
            $sql .= ", password = :password";
        }
        $sql .= " WHERE id = :id";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            if (!empty($password)) {
                $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            }
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            if (!empty($password)) {
                $param_password = password_hash($password, PASSWORD_DEFAULT);
            }
            $param_id = $user_id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $update_success = "Profile updated successfully.";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($conn);
}
?>

<div class="container mt-5">
    <h2>Profile</h2>
    <p>Update your profile information.</p>
    <?php
    if (!empty($update_success)) {
        echo '<div class="alert alert-success">' . $update_success . '</div>';
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password (Leave blank to keep current password)</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Update">
        </div>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>
