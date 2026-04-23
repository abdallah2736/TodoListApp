<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../../Config/DB.php';
require_once __DIR__ . '/../../Validation.php';

if (isset($_POST['Register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $errors = []; // An array to collect errors
    
    // Verify email uniqueness
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $checkEmail = $stmt->get_result();
    
    if ($checkEmail->num_rows > 0) {
        $errors[] = "Email is already registered!";
        $_SESSION['register_errors'] = $errors;
        header("Location: /TodoListApp/page/Register.php");
        exit();
    }
    
    if (!Validation::checkUsername($name)) { // Verify username
        $errors[] = "Only letters and white space allowed in username.";
    }
    
    if (!Validation::_checkEmail($email)) { // Verify email format
        $errors[] = "Invalid email format.";
    }
    
    if (!Validation::checkPassword($password)) { // Verify password
        $errors[] = "Password must be at least 8 characters long and contain at least one number.";
    }
    
    $stmt->close();
    
    // If there are errors
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        header("Location: /TodoListApp/page/Register.php"); 
        exit();
    }
    
    // Insert the new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $passwordHash);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: /TodoListApp/page/Login.php"); 
        exit();
    } else {
        $_SESSION['register_errors'] = ["Database error. Please try again later."];
        $stmt->close();
        header("Location: /TodoListApp/page/Register.php");
        exit();
    }
}
?>