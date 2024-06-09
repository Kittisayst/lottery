<?php
header('Content-Type: application/json');

if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'create':
            insert();
            break;
        case 'getpaymentlistbyID':
            getpaymentlistbyID();
            break;
        case 'getFinancialState':
            getFinancialState();
            break;
        case 'getSumFinancial':
            getSumFinancial();
            break;
        default:
            # code...
            break;
    }
}

function insert()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "INSERT INTO tb_paymentlist VALUES(?,?,?,?,?,?,?,?,?)";
    $stmt = $connect->prepare($sql);
    //ຮັບຂໍ້ມູນ
    $paymentID = $_POST['paymentID'];
    $FinancialID = $_POST['FinancialID'];
    $Cash = str_replace(',', '', $_POST['cash']);
    $Transfer = str_replace(',', '', $_POST['transfer']);
    $Repay = str_replace(',', '', $_POST['repay']);
    $Etc = str_replace(',', '', $_POST['etc']);
    $Comment = $_POST['comment'];
    //ຂໍ້ມູນໃນການບັນທຶກ
    $data =
        [
            null,
            $paymentID,
            $FinancialID,
            $Cash == "" ? "0" : $Cash,
            $Transfer == "" ? "0" : $Transfer,
            $Repay == "" ? "0" : $Repay,
            $Etc == "" ? "0" : $Etc,
            $Comment,
            0
        ];

    $stmt->execute($data);
    if ($stmt) {
        $conn->createJson('success', "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson('error', "ບັນທຶກຜິດພາດ", false);
    }
}

function getFinancialState()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT COALESCE(SUM(COALESCE(cash, 0) + COALESCE(transfer, 0)+ COALESCE(etc, 0)),0) AS sumMoney
    FROM tb_paymentlist
    WHERE FinancialID = ?';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['FinancialID']]);
    $result = $stmt->fetchAll();
    if ($stmt) {
        $conn->createJson($result[0], "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ບັນທຶກຜິດພາດ", false);
    }
}

function getSumFinancial()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT COALESCE(SUM(cash), 0) AS sumCash, COALESCE(SUM(transfer), 0) AS sumTransfer,
     COALESCE(SUM(etc), 0)AS sumEtc ,
     COALESCE(SUM(repay), 0)AS sumrepay ,
     COALESCE(GROUP_CONCAT(comment ORDER BY PaylistID),"") AS allcomment
     FROM tb_paymentlist WHERE FinancialID = ?';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['FinancialID']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($stmt) {
        $conn->createJson($result[0], "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ບັນທຶກຜິດພາດ", false);
    }
}

function getpaymentlistbyID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_paymentlist
    INNER JOIN tb_financail ON tb_paymentlist.FinancialID = tb_financail.FinancialID
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    WHERE paymentID=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['paymentID']]);
    $result = $stmt->fetchAll();
    if ($stmt) {
        $conn->createJson($result, "ດຶງຂໍ້ມູນການຖອກເງິນ", true);
    } else {
        $conn->createJson("error", "ດຶງຂໍ້ມູນການຖອກເງິນຜິດພາດ", false);
    }
}
