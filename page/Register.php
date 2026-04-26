<?php
require __DIR__ . '/../actions/authentication/register_action.php'; 
require __DIR__ . '/../lang.php';
$message = ""; 


if (isset($_SESSION['register_errors'])) {
    $message = "<p class='errorMessage'>";
    foreach($_SESSION['register_errors'] as $error) {
        $message .= "$error <br>";
    }
    $message .= "</p>";
    unset($_SESSION['register_errors']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title><?php echo $lang['Register']; ?></title>
</head>
<body>
    <div class="container">
        <div class="lang-links">
            <a href="Register.php?lang=en" class="lang-item">English</a>
            <span class="separator">|</span>
            <a href="Register.php?lang=ar" class="lang-item">العربية</a>
        </div>
        <!-- //! -->
        <div class="form-box" id="Register-form">
            <form  method="post">
                <h2><?php echo $lang['Register']; ?></h2>
                <?php echo $message ?>
                <input type="text" name="name" placeholder="<?php echo $lang['Name']; ?>" required> 
                <input type="email" name="email" placeholder="<?php echo $lang['Email']; ?>" required>
                <input type="password" name="password" placeholder="<?php echo $lang['Password']; ?>" required>
                <button type="submit" name="Register"><?php echo $lang['Register']; ?></button>
                <p><?php echo $lang['Already have an account?']; ?> <a href="Login.php"><?php echo $lang['Login']; ?></a></p>
            </form>
        </div>
    </div>
    <script src="../assets/js/scriptRegister.js"></script>
</body>
</html>
