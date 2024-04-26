<?php
header('Content-Type: application/json');

if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'create':
            create();
            break;
        case 'getbyid':
            getbyID();
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

function getbyID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_save_pdf_data AS sv
    INNER JOIN tb_unit AS un ON sv.unitID=un.unitID
    INNER JOIN tb_lottery AS lot ON sv.lotteryID = lot.lotteryID
    WHERE sv.savePDF_ID=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['id']]);
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn->createJson($result[0], "ຂໍ້ມູນ PDF", true);
    } else {
        $conn->createJson("error", "ບໍ່ພົບຂໍ້ມູນຈາກ ID " . $_GET['id'], false);
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