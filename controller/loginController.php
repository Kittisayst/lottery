<?php
class LoginController
{

    public function isLogin()
    {
        return isset($_COOKIE["user"]);
    }

    public function view()
    {
        include_once("./views/login.php");
    }
}
