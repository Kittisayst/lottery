<?php
header('Content-Type: application/json');
if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'all':
            getAll();
            break;
        case 'byid':
            getByID();
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

function getAll()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_permission";
    $stml = $connect->prepare($sql);
    $stml->execute();
    $result = $stml->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        $conn->createJson($result, "ດຶງຂໍ້ມູນສິດທິສຳເລັດ", true);
    } else {
        $conn->createJson([], "ດຶງຂໍ້ມູນສິດທິຜິດພາດ", false);
    }
}

function getByID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_permission WHERE permissionID=?";
    $stml = $connect->prepare($sql);
    $stml->execute([$_GET['id']]);
    $result = $stml->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        $conn->createJson($result[0], "ດຶງຂໍ້ມູນສິດທິສຳເລັດ", true);
    } else {
        $conn->createJson([], "ດຶງຂໍ້ມູນສິດທິຜິດພາດ", false);
    }
}

function create()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "INSERT INTO tb_permission VALUES(?,?,?)";
    $data = [null, $_POST['name'], $_POST['permission']];
    $stml = $connect->prepare($sql);
    $stml->execute($data);
    if ($stml) {
        $conn->createJson(1, "ບັນທຶກຂໍ້ມູນສິດທິສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ບັນທຶກຂໍ້ມູນສິດທິຜິດພາດ", false);
    }
}
function update()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "UPDATE tb_permission SET name=?, permission=? WHERE permissionID=?";
    $data = [$_POST['name'], $_POST['permission'], $_GET['id']];
    $stml = $connect->prepare($sql);
    $stml->execute($data);
    if ($stml) {
        $conn->createJson(1, "ແກ້ໄຂຂໍ້ມູນສິດທິສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ແກ້ໄຂຂໍ້ມູນສິດທິຜິດພາດ", false);
    }
}

