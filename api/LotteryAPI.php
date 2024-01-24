<?php
header('Content-Type: application/json');
if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'getlotterys':
            getLotterys();
            break;
        case 'getlotteryByID':
            $ID = $_GET['lotid'];
            getLotteryByID($ID);
            break;
        case 'create':
            create();
            break;
        case 'update':
            update();
            break;
        default:
            # code...
            break;
    }
}

function getLotterys()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_lottery ORDER BY lotteryNo DESC";
    $stm = $connect->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຂໍ້ມູນງວດ'", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນງວດ", false);
    }
}

function getLotteryByID($ID)
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_lottery WHERE lotteryID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$ID]);
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຂໍ້ມູນງວດ'", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນງວດ", false);
    }
}

function create()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $lotdata = [null, $_POST['lotteryNo'], $_POST['lotteryCorrect'], $_POST['lotDate']];
    if (checking($_POST['lotteryNo'])) {
        $conn->createJson('warning', "ເລກທີ່ຊ້ຳກັນ", true);
    } else {
        $sql = "INSERT INTO tb_lottery VALUES(?,?,?,?)";
        $stm = $connect->prepare($sql);
        $stm->execute($lotdata);
        if ($stm) {
            $conn->createJson('success', "ເພີ່ມຂໍ້ມູນງວດສຳເລັດ", true);
        } else {
            $conn->createJson('error', "ເພີ່ມຂໍ້ມູນງວດຜິດພາດ", false);
        }
    }
}

function update()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $lotdata = [$_POST['lotteryNo'], $_POST['lotteryCorrect'], $_POST['lotDate'], $_POST['lotteryID']];
    $sql = "UPDATE tb_lottery SET lotteryNo=?,lotteryCorrect=?,lotDate=? WHERE lotteryID=?";
    $stm = $connect->prepare($sql);
    $stm->execute($lotdata);
    if ($stm) {
        $conn->createJson('success', "ແກ້ໄຂງວດສຳເລັດ", true);
    } else {
        $conn->createJson('error', "ແກ້ໄຂງວດຜິດພາດ", false);
    }
}

function checking($lotteryNo)
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COUNT(*) AS lotCount FROM tb_lottery WHERE lotteryNo=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$lotteryNo]);
    $result = $stmt->fetchAll();
    return $result[0]['lotCount'] > 0;
}

function checkingUpdate($lotteryNo)
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COUNT(*) AS lotCount FROM tb_lottery WHERE lotteryNo=?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$lotteryNo]);
    $result = $stmt->fetchAll();
    return $result[0]['lotCount'];
}
