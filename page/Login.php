<?php require __DIR__ . '/../lang.php'; ?>
<?php require __DIR__ . '/../Config/SESSION.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title><?php echo $lang['Login']; ?></title>
</head>
<body>
    <div class="container">
        <div class="lang-links">
            <a href="Login.php?lang=en" class="lang-item">English</a>
            <span class="separator">|</span>
            <a href="Login.php?lang=ar" class="lang-item">العربية</a>
        </div>
        <div class="form-box" id="login-form">
            <form method="post">
                <h2><?php echo $lang['Login']; ?></h2>
                <p class='errorMessage' style="display: none;"></p>
                <input type="email" name="email" placeholder="<?php echo $lang['Email']; ?>" required>
                <input type="password" name="password" placeholder="<?php echo $lang['Password']; ?>" required>
                <button type="submit" id="login" name="login"><?php echo $lang['Login']; ?></button>
                <p><?php echo $lang["Don't have an account?"]; ?> <a href="Register.php"><?php echo $lang['Register']; ?></a></p>
            </form>
        </div>
        <script src="../assets/js/scriptLogin.js"></script>
</body>
</html>