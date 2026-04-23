<?php
echo "Logging out...";
session_start();
session_unset();
session_destroy();
header("Location: /TodoListApp/page/homepage.php");
exit();
?>