<?php
session_start();

require_once __DIR__ . '/../DB.php';
require_once __DIR__ . '/../Validation.php';

if (isset($_POST['Register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");

    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
    } elseif (!Validation::checkUsername($_POST['name']) || !Validation::_checkEmail($_POST['email']) || !Validation::checkPassword($_POST['password'])) {
        if (Validation::checkPassword($_POST['password']) == false) {
            $_SESSION['register_error'] = "Password must be at least 8 characters long and contain at least one number.";
            header("Location: ../TodoListApp/Register.php");
            exit();
        } elseif (Validation::checkUsername($_POST['name']) == false) {
            $_SESSION['register_error'] = "Only letters and white space allowed in username.";
            header("Location: ../TodoListApp/Register.php");
            exit();
        } elseif (Validation::_checkEmail($_POST['email']) == false) {
            $_SESSION['register_error'] = "Invalid email format.";
            header("Location: ../TodoListApp/Register.php");
            exit();
        }
        header("Location: ../TodoListApp/Register.php");
        exit();
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql)) {
            $_SESSION['register_success'] = 'Registration successful!';
        }
    }

    header("Location: ../TodoListApp/Register.php");
    exit();
}
?>