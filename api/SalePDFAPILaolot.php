<?php
header('Content-Type: application/json');

if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'create':
            create();
            break;
        case 'update':
            update();
            break;
        case 'delete':
            delete();
            break;
        case 'getbylotteryno':
            getByLotteryNo();
            break;
        case 'getbyid':
            getbyid();
            break;
        default:
            # code...
            break;
    }
}

function create()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $date = DateTime::createFromFormat('d/m/Y', $_POST['lotDate']);
    $formattedDate = $date->format('Y-m-d');
    $db = $conn->getConnection();
    if (!isSame($_POST['lotteryNo'])) {
        $date = DateTime::createFromFormat('d/m/Y', $_POST['lotDate']);
        $formattedDate = $date->format('Y-m-d');
        $data = [
            null,
            $_POST['lotteryID'],
            $_POST['lotteryNo'],
            $formattedDate,
            $_POST['FileName'],
            $_POST['fileSize'],
            $_POST['pdfData'],
            $_POST['UserID'],
        ];
        $sql = "INSERT INTO tb_salepdflaolot VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        if ($stmt) {
            $conn->createJson(getMaxID(), "ບັນທຶກຂໍ້ມູນ PDF ການຂາຍ ສຳເລັດ", true);
        } else {
            $conn->createJson($data, "ບັນທຶກຜິດພາດ", false);
        }
    } else {
        $conn->createJson("warning", "ເລກທີ່ " . $_POST['lotteryNo'] . " ໄດ້ບັນທຶກແລ້ວ", false);
    }

}

function update()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $date = DateTime::createFromFormat('d/m/Y', $_POST['lotDate']);
    $formattedDate = $date->format('Y-m-d');
    $data = [$_POST['lotteryID'], $_POST['lotteryNo'], $formattedDate, $_GET['id']];
    $sql = "UPDATE tb_salepdflaolot SET lotteryID=?,lotteryNo=?,lotDate=? WHERE salePDFID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    if ($stmt) {
        $conn->createJson("success", "ແກ້ໄຂ PDF ການຂາຍ ສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ແກ້ໄຂ PDF ການຂາຍ ຜິດພາດ", false);
    }
}

function delete()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "DELETE FROM tb_salepdflaolot WHERE salePDFID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['id']]);
    if ($stmt) {
        $conn->createJson("success", "ລົບຂໍ້ມູນ PDF ການຂາຍ ສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ລົບຂໍ້ມູນ PDF ການຂາຍ ຜິດພາດ", true);
    }
}

function getMaxID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COALESCE(MAX(salePDFID), 1) AS maxid FROM tb_salepdflaolot";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result[0]['maxid'];
}

function isSame($lotno)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT salePDFID FROM tb_salepdflaolot WHERE lotteryNo=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$lotno]);
    return $stmt->rowCount() > 0;
}

function getByLotteryNo()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_salepdflaolot WHERE lotteryID=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['lotno']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        $conn->createJson($result, "ຂໍ້ມູນ PDF ການຂາຍ", true);
    } else {
        $conn->createJson([], "ຂໍ້ມູນ PDF ການຂາຍ", false);
    }
}


function getbyid()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_salepdflaolot WHERE salePDFID=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        $conn->createJson($result[0], "ຂໍ້ມູນ PDF ການຂາຍ", true);
    } else {
        $conn->createJson([], "ຂໍ້ມູນ PDF ການຂາຍ", false);
    }
}