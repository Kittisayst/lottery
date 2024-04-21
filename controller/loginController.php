<?php
class LoginController
{

    public function isLogin()
    {
        if (!empty($_COOKIE['user'])) {
            $cookieValue = $_COOKIE['user'];
            $expiration_time = time() + 3600;
            setcookie("user", $cookieValue, $expiration_time, "/");
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        include_once ("./views/login.php");
    }
}
