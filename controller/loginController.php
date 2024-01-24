<?php
session_start();
class LoginController
{

    public function isLogin()
    {
        return isset($_SESSION['user']);
    }

    public function Login($user, $pass)
    {
    }

    public function view()
    {
        include_once("./views/login.php");
    }
}
