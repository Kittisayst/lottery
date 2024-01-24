<?php
header('Content-Type: application/json');
if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'create':
            create();
            break;
        case 'getpaymentNo':
            PaymentNo();
            break;
        case 'isPayment':
            IsPayment();
            break;
        case 'search':
            serachPayment();
            break;
        default:
            # code...
            break;
    }
}

function create()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "INSERT INTO tb_payment VALUES(?,?,?,?,?,?,?,?,?)";
    $stm = $connect->prepare($sql);
    $cash = str_replace(',', '', $_POST['Cash']);
    $transfer = str_replace(',', '', $_POST['Transfer']);
    $etc = str_replace(',', '', $_POST['Etc'] == "" ? "0" : $_POST['Etc']);
    date_default_timezone_set('Asia/Vientiane');
    $Data = [null, $_POST['FinancialID'], $_POST['paymentNo'], $cash, $transfer, $etc, $_POST['Comment'], date("Y-m-d H:i:s"), $_POST['UserID']];
    $stm->execute($Data);
    if ($stm) {
        $conn->createJson(getLastID(), "ບັນທຶກຂໍ້ມູນການຖອກເງິນສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ບັນທຶກຂໍ້ມູນການຖອກເງິນຜິດພາດ", false);
    }
}

function IsPayment()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_payment WHERE FinancialID=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['fid']]);
    $result = $stmt->fetchAll();
    $conn->createJson($result, "ispayment", true);
}

function PaymentNo()
{
    require_once("../database/connectDB.php");
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
    $conn->createJson($result[0], "payment", true);
}

function serachPayment()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_financail
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    INNER JOIN tb_province ON tb_unit.provinceID = tb_province.pid
    INNER JOIN tb_lottery ON tb_financail.lotteryID = tb_lottery.lotteryID
    WHERE tb_unit.provinceID=? AND tb_financail.lotteryID=? ORDER BY tb_unit.unitID';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_POST['provinceID'], $_POST['paymentID']]);
    $result = $stmt->fetchAll();
    if ($result) {
        $conn->createJson($result, "search", true);
    } else {
        $conn->createJson(0, "search", false);
    }
}

function getLastID()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT MAX(paymentID) AS payid FROM tb_payment";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result[0]['payid'];
}
