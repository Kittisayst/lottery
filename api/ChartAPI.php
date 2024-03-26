<?php
// header('Content-Type: application/json');
if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'sales':
            sales();
            break;
        case 'lottery':
            getLottery();
            break;
        default:
            # code...
            break;
    }
}

function sales()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT lotteryID FROM tb_lottery ORDER BY lotteryID DESC LIMIT 15";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $saleData = array();
    foreach ($result as $lot) {
        array_push($saleData, getSale($lot['lotteryID']));
    }
    $conn->createJson($saleData, "ຍອດຂາຍ", true);
}

function getSale($lotteryID)
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COALESCE(SUM(Sales),0) AS total FROM tb_financail WHERE lotteryID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$lotteryID]);
    $result = $stmt->fetchAll();
    return $result[0]['total'];
}

function getLottery()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT lotteryID FROM tb_lottery ORDER BY lotteryID DESC LIMIT 15";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $lotarr = array();
    foreach ($result as $lot) {
        array_push($lotarr, "ງວດທີ ".$lot['lotteryID']);
    }
    $conn->createJson($lotarr, "ງວດທີ", true);
}
