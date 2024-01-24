<?php
require_once("./database/connectDB.php");
$conn = new connectDB();
$connect = $conn->getConnection();
$sql = 'SELECT IFNULL(
        (SELECT MAX(CAST(SUBSTRING(paymentNo, 3) AS UNSIGNED)) AS max_NO 
         FROM tb_payment 
         WHERE DATE(SaveDate) = CURDATE()), 
        (SELECT MAX(CAST(SUBSTRING(paymentNo, 3) AS UNSIGNED))+1 AS max_NO FROM tb_payment)
    ) AS max_NO';
$stmt = $connect->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
echo "KF".$result[0]["max_NO"];
