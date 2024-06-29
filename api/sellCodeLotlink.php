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
        case 'getall':
            getall();
            break;
        case 'getunitprovinceall':
            getunitprovinceall();
            break;
        case 'getbyid':
            selectByID();
            break;
        case 'getbyunitid':
            selectByUnitID();
            break;
        default:
            # code...
            break;
    }
}

function getall()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "SELECT * FROM tb_machinelotlink";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if ($result) {
        $conn->createJson($result, "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson(0, "ບັນທຶກຜິດພາດ", false);
    }
}

function getunitprovinceall()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "SELECT * FROM tb_machinelotlink
    INNER JOIN tb_unit ON tb_machinelotlink.UnitID = tb_unit.unitID
    INNER JOIN tb_province ON tb_unit.provinceID = tb_province.pid WHERE tb_machinelotlink.UnitID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        $conn->createJson($result, "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson(0, "ບັນທຶກຜິດພາດ", false);
    }
}

function create()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    if (checkID($_POST['sellcode'])) {
        $data = [null, $_POST['unid'], $_POST['sellcode']];
        $sql = "INSERT INTO tb_machinelotlink VALUES(?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        if ($stmt) {
            $conn->createJson("success", "ບັນທຶກສຳເລັດ", true);
        } else {
            $conn->createJson($data, "ບັນທຶກຜິດພາດ", false);
        }
    } else {
        $conn->createJson(0, "ລະຫັດຜູ້ຂາຍນີ້ມີຢູ່ແລ້ວ", false);
    }
}

function update()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $data = [$_POST['unid'], $_POST['sellcode'], $_GET['id']];
    $sql = "UPDATE tb_machinelotlink SET UnitID=?,machineCode=? WHERE machineID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    if ($stmt) {
        $conn->createJson("success", "ແກ້ໄຂຂໍ້ມູນສຳເລັດ", true);
    } else {
        $conn->createJson($data, "ບັນທຶກຜິດພາດ", false);
    }
}

function selectByUnitID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "SELECT * FROM tb_machinelotlink WHERE UnitID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        $conn->createJson($result, "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson(0, "ບັນທຶກຜິດພາດ", false);
    }
}

function selectByID()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "SELECT * FROM tb_machinelotlink
    INNER JOIN tb_unit ON tb_machinelotlink.UnitID = tb_unit.unitID
    WHERE machineID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll();
    if ($result) {
        $conn->createJson($result, "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson(0, "ບັນທຶກຜິດພາດ", false);
    }
}

function delete()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "DELETE FROM tb_machinelotlink WHERE machineID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['id']]);
    if ($stmt) {
        $conn->createJson("success", "ລົບຂໍ້ມູນລະຫັດຜູ້ຂາຍສຳເລັດ", true);
    } else {
        $conn->createJson($data, "ລົບຂໍ້ມູນລະຫັດຜູ້ຂາຍຜິດພາດ", false);
    }
}

function checkID($code)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $sql = "SELECT * FROM tb_machinelotlink WHERE machineCode=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$code]);
    $stmt->fetchAll();
    return $stmt->rowCount() <= 0;
}