<?php
function getLotteryNo()
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $stmt = $db->prepare("SELECT * FROM tb_lottery WHERE lotteryID=?");
    $stmt->execute([$_GET['lotid']]);
    $result = $stmt->fetchAll();
    return $result[0];
}
