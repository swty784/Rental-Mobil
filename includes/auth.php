<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE (username = :username OR email = :username)");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($_SESSION['role'] === 'admin') {
                    header('Location: ../admin/index.php'); 
                } else {
                    header('Location: ../user/index.php'); 
                }
                exit();
            } else {
                echo "<p>Invalid password. Please try again.</p>";
            }
        } else {
            echo "<p>User not found. Please check your username/email.</p>";
        }
    } else {
        echo "<p>Please fill in both fields.</p>";
    }
}
?>