<?php
header('Content-Type: application/json');

if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'create':
            create();
            break;
        case 'delete':
            delete();
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
    $db = $conn->getConnection();
    if (!isSame($_POST['lotteryNo'])) {
        $data = [
            null,
            $_POST['lotteryNo'],
            date("Y-m-d", strtotime($_POST['lotDate'])),
            $_POST['FileName'],
            $_POST['fileSize'],
            $_POST['pdfData'],
            $_POST['UserID'],
        ];
        $sql = "INSERT INTO tb_salepdf VALUES(?,?,?,?,?,?,?)";
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

function delete()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "DELETE FROM tb_salepdf WHERE salePDFID=?";
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
    $sql = "SELECT COALESCE(MAX(salePDFID), 1) AS maxid FROM tb_salepdf";
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
    $sql = "SELECT salePDFID FROM tb_salepdf WHERE lotteryNo=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$lotno]);
    return $stmt->rowCount() > 0;
}