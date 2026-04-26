<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Config/DB.php';

// التحقق من وجود البيانات
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Content-Type: application/json');
    echo json_encode([
        "message" => "Please fill in all fields.",
        "status" => false,
        "page" => "Login"
    ]);
    exit();
}

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Content-Type: application/json');
    echo json_encode([
        "message" => "Please fill in all fields.",
        "status" => false,
        "page" => "Login"
    ]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['id'] = $user['id'];             
        
        header('Content-Type: application/json');
        echo json_encode([
            "message" => "Login successful",
            "status" => true,
            "page" => "homepage"
        ]);
        exit();
    }
}

header('Content-Type: application/json');
echo json_encode([
    "message" => "Incorrect email or password",
    "status" => false,
    "page" => "Login",
    "email" => $email
]);