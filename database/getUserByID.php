<?php
function getUserData()
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $stmt = $db->prepare("SELECT * FROM tb_user WHERE userID=?");
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll();
    $unitData = $result[0];
    return $unitData;
}