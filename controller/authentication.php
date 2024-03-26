<?php
session_start();
require_once ("../database/connectDB.php");
$conn = new connectDB();
$connect = $conn->getConnection();
$sql = "SELECT * FROM tb_user WHERE User=? AND Password=?";
$stm = $connect->prepare($sql);
$stm->execute([$_POST['txtuser'], $_POST['password']]);
$result = $stm->fetchAll();
if ($result) {
    $expiration_time = time() + 3600;
    // Set the cookie with the calculated expiration time
    setcookie("user", $result[0]['userID'], $expiration_time, "/");
    // Redirect user to the homepage
    header("Location: /lottery/?page=home");
    exit(); // Stop further execution
} else {
    header("Location: /lottery/?page=home");
}
