<?php
class Validation {
    public static function checkPassword($password) {
        if (strlen($password) < 8 ||!preg_match("#[0-9]+#", $password)) {
            return false;
        }
        return true;
    }

    public static function checkUsername($username) {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
            return false; 
        }
        return true;
    }

    public static function _checkEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function data($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public static function html($input) {
        $input = trim($input);
        // السماح بوسوم HTML الآمنة فقط
        $allowed_tags = '<p><br><b><i><u><strike><ul><ol><li><a><strong><em><span><div>';
        $input = strip_tags($input, $allowed_tags);
        return $input;
    }

}
?>