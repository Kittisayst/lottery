<?php

function getProvinceTable(){
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $stmt = $db->prepare("SELECT * FROM tb_province");
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}