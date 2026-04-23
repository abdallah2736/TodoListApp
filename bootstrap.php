<?php
require_once __DIR__ . "/Config/SESSION.php";

if (!isset($_SESSION['email'], $_SESSION['name'],$_SESSION['id'])) {
    // require_once __DIR__ . "/TodoListApp/page/Login.php";
    header("Location: /TodoListApp/page/Login.php");
    exit();
}