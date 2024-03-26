<?php
session_start();
var_dump($_POST['txtuser']);
var_dump($_POST['password']);
if ($_POST['txtuser'] == "Boss" && $_POST['password'] == "123") {
    $_SESSION['user'] = "Boss";
} else {
    $_SESSION['ms'] = "Boss";
}
