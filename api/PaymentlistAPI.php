<?php
header('Content-Type: application/json');

if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'create':
            insert();
            break;
        default:
            # code...
            break;
    }
}

function insert()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "INSERT INTO tb_paylist VALUES(?,?,?,?,?,?,?,?)";
    date_default_timezone_set('Asia/Vientiane');
    $paymentID = $_POST['paymentID'];
    $FinancialID = $_POST['FinancialID'];
    $Cash = str_replace(',', '', $_POST['Cash']);
    $Transfer =  str_replace(',', '', $_POST['Transfer']);
    $Other = str_replace(',', '', $_POST['Other']);
    $SaveDate = date("Y-m-d H:i:s");
    $Comment = $_POST['Comment'];
    $data = [null, $paymentID, $FinancialID, $Cash == "" ? "0" : $Cash, $Transfer == "" ? "0" : $Transfer, $Other == "" ? "0" : $Other, $SaveDate, $Comment];
    $stmt = $connect->prepare($sql);
    $stmt->execute($data);
    if ($stmt) {
        $conn->createJson('success', "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson('error', "ບັນທຶກຜິດພາດ", false);
    }
}
