<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isUserLoggedIn(){
    if (isset($_SESSION['email'], $_SESSION['name'], $_SESSION['id'])) {
        return true;
    }
    return false;
}

function hasActiveSession(){
    if(!isUserLoggedIn()){
        header("Location: /TodoListApp/page/Login.php");
        exit(); 
    }
}

