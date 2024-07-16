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
    $userID = $_POST['userID'];
    $permissionID = $_POST['permissionID'];
    if (!isSave($userID)) {
        $sql = "INSERT INTO tb_userpermission VALUES(?,?,?)";
        $stm = $connect->prepare($sql);
        $Data = [null, $userID, $permissionID];
    } else {
        $sql = "UPDATE tb_userpermission SET permissionID=? WHERE userID=?";
        $stm = $connect->prepare($sql);
        $Data = [$permissionID, $userID];
    }
    $stm->execute($Data);
    if ($stm) {
        $conn->createJson('', "ເພີ່ມຂໍ້ມູນຜູ້ໃຊ້ງານສຳເລັດ", true);
    } else {
        $conn->createJson('', "ເພີ່ມຂໍ້ມູນຜູ້ໃຊ້ງານຜິດພາດ", false);
    }
}

function isSave($userID)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT userID FROM tb_userpermission WHERE userID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$userID]);
    return $stm->rowCount() > 0;
}