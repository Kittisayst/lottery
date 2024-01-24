<?php
function getTicket()
{
    require_once("../database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = 'SELECT * FROM tb_payment
    INNER JOIN tb_financail ON tb_payment.FinancialID=tb_financail.FinancialID
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    INNER JOIN tb_province ON tb_unit.provinceID = tb_province.pid
    INNER JOIN tb_lottery ON tb_financail.lotteryID = tb_lottery.lotteryID
    WHERE paymentID=?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['paymentid']]);
    $result = $stmt->fetchAll();
    return $result[0];
}
