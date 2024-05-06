<?php
header('Content-Type: application/json');
if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'getByID':

            break;
        case 'create':
            create();
            break;
        case 'getbyuserid':
            getByUserID();
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
    $lotdata = [null, $_POST['lotno'], $_POST['lotdate'], $_POST['correct'], $_POST['pdfdata'], $_POST['title'], $_POST['userID']];
    if (checking($_POST['title'], $_POST['userID'])) {
        $conn->createJson('warning', "ທ່ານໄດ້ບັນທຶກ PDF ລາງວັນນີ້ແລ້ວ", true);
    } else {
        $sql = "INSERT INTO tb_lotcorrectpdf VALUES(?,?,?,?,?,?,?)";
        $stm = $connect->prepare($sql);
        $stm->execute($lotdata);
        if ($stm) {
            $conn->createJson('success', "ເພີ່ມຂໍ້ມູນ PDF ຖືກລາງວັນສຳເລັດ", true);
        } else {
            $conn->createJson('error', "ເພີ່ມຂໍ້ມູນ PDF ຖືກລາງວັນຜິດພາດ", false);
        }
    }
}

function getByUserID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_lotcorrectpdf WHERE userID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$_GET['id']]);
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    if ($stm) {
        $conn->createJson($result, "ສະແດງຂໍ້ມູນ PDF ຖືກເລກ", true);
    } else {
        $conn->createJson('error', "ເພີ່ມຂໍ້ມູນ PDF ຖືກລາງວັນຜິດພາດ", false);
    }
}

function checking($lotteryNo, $userID)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COUNT(*) AS lotCount FROM tb_lotcorrectpdf WHERE title=? AND userID=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$lotteryNo, $userID]);
    $result = $stmt->fetchAll();
    return $result[0]['lotCount'] > 0;
}