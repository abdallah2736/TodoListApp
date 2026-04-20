<?php
session_start(); 
require_once __DIR__ . '/../DB.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (isset($_POST['login'])) {

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['id'] = $user['id']; //
            header("Location: ../TodoListApp/index.php");
            exit();
        }
    }
    
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION['login_error'] = "Please fill in all fields.";
        header("Location: ../TodoListApp/Login.php");
        exit();
    }

    //اذا كنت كلمه السر خطا او الحساب غير موجود
    $_SESSION['login_error'] = 'Incorrect email or password';
    $stmt->close();
    header("Location: ../TodoListApp/Login.php");
    exit();
}
