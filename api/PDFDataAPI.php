<?php
header('Content-Type: application/json');

if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'create':
            create();
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
    $connect = $conn->getConnection();
    if (!isSame($_POST['unitID'], $_POST['lotteryID'])) {
        $sql = "INSERT INTO tb_save_pdf_data VALUES(?,?,?,?,?,?)";
        $stmt = $connect->prepare($sql);
        $data = [null, $_POST['salePDFID'], $_POST['unitID'], $_POST['lotteryID'], $_POST['comment'], $_POST['pdfData']];
        $stmt->execute($data);
        if ($stmt->rowCount() > 0) {
            $conn->createJson(getMaxID(), "ບັນທຶກຂໍ້ມູນ PDF ສຳເລັດ", true);
        } else {
            $conn->createJson("error", "ບັນທຶກຂໍ້ມູນ PDF ສຳເລັດ", false);
        }
    } else {
        $conn->createJson("warning", "ຂໍ້ມູນ PDF ໜ່ວຍນີ້ ບັນທຶກແລ້ວ!", false);
    }
}

function getMaxID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COALESCE(MAX(savePDF_ID), 1) AS maxid FROM tb_save_pdf_data";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result[0]['maxid'];
}

function isSame($unitID, $lotteryID)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT savePDF_ID FROM tb_save_pdf_data WHERE unitID=? AND lotteryID=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$unitID, $lotteryID]);
    return $stmt->rowCount() > 0;
}