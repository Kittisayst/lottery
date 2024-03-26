<?php
function getUser($id)
{
    require_once ("./database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql ="SELECT * FROM tb_user WHERE userid=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetchAll();
    return $result[0];
}

function getUserPrint($id)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql ="SELECT * FROM tb_user WHERE userid=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetchAll();
    return $result[0];
}
