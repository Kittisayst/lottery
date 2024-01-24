<?php
require_once("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$stmt = $db->prepare("SELECT MAX(lotteryNo)+1 AS lotMax FROM tb_lottery");
$stmt->execute();
$result = $stmt->fetchAll();
if ($result) {
    echo $result[0]['lotMax'];
} else {
    echo 0;
}
