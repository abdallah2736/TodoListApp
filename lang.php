<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require get_language_file();
function get_language_file()
{
    $_SESSION['lang'] = $_SESSION['lang'] ?? 'en';
    $_SESSION['lang'] = $_GET['lang'] ?? $_SESSION['lang'];

    // Get the root directory of the project
    $rootDir = dirname(__FILE__);
    return $rootDir . "/languages/" . $_SESSION['lang'] . ".php";
}