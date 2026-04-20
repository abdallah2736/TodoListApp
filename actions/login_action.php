<?php
session_start(); 
require_once __DIR__ . '/../DB.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Please fill in all fields.";
        header("Location: ../Login.php"); 
        exit();
    }

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['id'] = $user['id']; 
            header("Location: ../index.php"); 
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    header("Location: ../Login.php");
    exit();
}