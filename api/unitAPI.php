<?php
header('Content-Type: application/json');
if (isset($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'getunits':
            getUnits();
            break;
        case 'unitslimit':
            unitslimit();
            break;
        case 'getunitbyID':
            $unitID = $_GET['unitID'];
            getUnitsbyUnitID($unitID);
            break;
        case 'getUnitsbyProvinID':
            $provinceID = $_GET['provinceID'];
            getUnitsbyProvinID($provinceID);
            break;
        case 'create':
            create();
            break;
        case 'update':
            update();
            break;
        case 'updateState':
            updateState();
            break;
        case 'delete':
            delete();
            break;
        case 'search':
            search();
            break;
        case 'percentage':
            percentage();
            break;
        case 'unitbyprovinid':
            getUnitByProvinceID();
            break;
        default:
            # code...
            break;
    }
}

function getUnits()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_unit
    INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID';
    $stm = $connect->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຫົວໜ່ວຍ", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນຫົວໜ່ວຍ", false);
    }
}

function unitslimit()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_unit
    INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID ORDER BY unitID ASC LIMIT ' . $_GET['limit'];
    $stm = $connect->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຫົວໜ່ວຍ", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນຫົວໜ່ວຍ", false);
    }
}

function getUnitsbyUnitID($unitID)
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_unit
    INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID WHERE tb_unit.unitID =?';
    $stm = $connect->prepare($sql);
    $stm->execute([$unitID]);
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຫົວໜ່ວຍ", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນຫົວໜ່ວຍ", false);
    }
}

function getUnitsbyProvinID($id)
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_unit WHERE provinceID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$id]);
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result, "ຫົວໜ່ວຍ", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນຫົວໜ່ວຍ", false);
    }
}

function create()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $data = [null, $_POST['cbprovinces'], $_POST['unitname'], $_POST['credit'], isset($_POST['moneyState']) ? 1 : 0, $_POST['Percentage']];
    $sql = "INSERT INTO tb_unit VALUES(?,?,?,?,?,?)";
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    if ($stmt) {
        $conn->createJson($data, "ບັນທຶກສຳເລັດ", true);
    } else {
        $conn->createJson($data, "ບັນທຶກຜິດພາດ", false);
    }
}

function update()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $id = $_GET['id'];
    $data = [$_POST['cbprovinces'], $_POST['unitname'], $_POST['credit'], isset($_POST['moneyState']) ? 1 : 0, $_POST['Percentage'], $id];
    $sql = "UPDATE tb_unit SET provinceID=?,unitName=?,credit=?,withdrawn=?,Percentage=? WHERE unitID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    if ($stmt) {
        $conn->createJson($data, "ແກ້ໄຂຂໍ້ມູນໜ່ວຍສຳເລັດ", true);
    } else {
        $conn->createJson($data, "ແກ້ໄຂຜິດພາດ", false);
    }
}

function updateState()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $id = $_GET['id'];
    $sql = "UPDATE `tb_unit` SET `withdrawn` = ? WHERE `tb_unit`.`unitID` = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_POST['moneyState'], $id]);
    if ($stmt) {
        $conn->createJson([[$_POST['moneyState'], $id]], "ok", true);
    } else {
        $conn->createJson("update", "ok", false);
    }
}

function delete()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    $id = $_GET['id'];
    $sql = "DELETE FROM tb_unit WHERE `tb_unit`.`unitID` = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    if ($stmt) {
        $_SESSION['success'] = "ລົບຂໍ້ມູນໜ່ວຍສຳເລັດ";
        $conn->createJson("ລົບຂໍ້ມູນໜ່ວຍ: $id", "delete", true);
    } else {
        $_SESSION['success'] = "ລົບຂໍ້ມູນໜ່ວຍຜິດພາດ";
        $conn->createJson("update", "delete", false);
    }
}

function search()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $db = $conn->getConnection();
    if ($_POST['search'] == "") {
        $sql = "SELECT * FROM tb_unit INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID WHERE provinceID=? ORDER by UnitID ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$_POST['provinceID']]);
        $result = $stmt->fetchAll();
        $conn->createJson($result, "ຄົ້ນຫາຂໍ້ມູນໜ່ວຍສະເພາະແຂວງ", true);
    } else {
        $sql = "SELECT * FROM tb_unit INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID WHERE provinceID=? AND unitName LIKE ? ORDER by UnitID ASC";
        $stmt = $db->prepare($sql);
        $serachData = [$_POST['provinceID'], "%" . $_POST['search'] . "%"];
        $stmt->execute($serachData);
        $result = $stmt->fetchAll();
        $conn->createJson($result, "ຄົ້ນຫາຂໍ້ມູນໜ່ວຍແຂວງ+ຊື່ໜ່ວຍ", true);
    }
}

function percentage()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    if (isset($_GET['unitid'])) {
        $unitID = $_GET['unitid'];
        $db = $conn->getConnection();
        $sql = "SELECT * FROM tb_unit WHERE unitID=?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$unitID]);
        $result = $stmt->fetchAll();
        $conn->createJson($result[0]['Percentage'], "get unit", true);
    } else {
        $conn->createJson(0, "not unitid", false);
    }
}

function getUnitByProvinceID()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    if (isset($_GET['pid'])) {
        $provinceID = $_GET['pid'];
        $db = $conn->getConnection();
        $sql = $provinceID === '0' ? "SELECT * FROM tb_unit" : "SELECT * FROM tb_unit WHERE provinceID=?";
        $stmt = $db->prepare($sql);
        $stmt->execute($provinceID === '0' ? null : [$provinceID]);
        $result = $stmt->fetchAll();
        $conn->createJson($result, "get unit by procinceID ", true);
    } else {
        $conn->createJson(0, "not unitid", false);
    }
}
