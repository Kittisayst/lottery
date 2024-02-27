<?php
function getBillNo()
{
    require_once("./database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT COALESCE(MAX(paymentID)+1, 1) AS BillNo FROM tb_payment';
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo $result[0]["BillNo"];
}
