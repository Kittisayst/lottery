<?php
header('Content-Type: application/json');
if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'getprovinces':
            getProvinces();
            break;
        case 'getprovincesbyID':
            $id = $_GET['id'];
            getProvincesByID($id);
            break;
        default:
            # code...
            break;
    }
}

function getProvinces()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_province";
    $stm = $connect->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຂໍ້ມູນແຂວງ", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນແຂວງ", false);
    }
}

function getProvincesByID($id)
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_province WHERE pid=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$id]);
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຂໍ້ມູນແຂວງ", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນແຂວງ", false);
    }
}
