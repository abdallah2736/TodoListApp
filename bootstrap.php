<?php
require_once __DIR__ . "/Config/SESSION.php";

if(!isUserLoggedIn()){
    header("Location: page/Login.php");
    exit();
}

header("Location: page/homepage.php");
exit();