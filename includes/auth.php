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

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] === 'blocked') {
                echo "Akun telah diblokir";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($_SESSION['role'] === 'admin') {
                    header('Location: ../admin/index.php'); 
                } else {
                    header('Location: ../user/index.php'); 
                }
                exit();
            }
        } else {
            echo "Invalid username & password.";
        }
    }
}
?>
