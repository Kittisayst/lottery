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
        case 'getfinancial':
            getfinancial();
            break;
        case 'getfinancials':
            getFinancials();
            break;
        case 'getreportfinancials':
            getReportFinancials();
            break;
        case 'getfinancialbyunitid':
            getfinancialID();
            break;
        case 'getReprotFinancialSearch':
            getReprotFinancialSearch();
            break;
        default:
            # code...
            break;
    }
}

function create()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    if (cheking() > 0) {
        $conn->createJson("warning", "ທ່ານໄດ້ປ້ອນຂໍ້ມູນໜ່ວຍນີ້ແລ້ວ", false);
    } else {
        $connect = $conn->getConnection();
        $sql = "INSERT INTO tb_financail VALUES(?,?,?,?,?,?,?,?,?)";
        $stm = $connect->prepare($sql);
        $sales = str_replace(',', '', $_POST['Sales']);
        $Award = str_replace(',', '', $_POST['Award']);
        $Data = [null, $_POST['unitID'], $_GET['id'], $sales, $_POST['Percentage'], $Award, $_POST['Awardno'], $_POST['SaveDate'], $_POST['userID']];
        $stm->execute($Data);
        if ($stm) {
            $conn->createJson("success", "ບັນທຶກຂໍ້ມູນສຳເລັດ", true);
        } else {
            $conn->createJson("error", "ບັນທຶກຂໍ້ມູນຜິດພາດ", false);
        }
    }
}

function update()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "UPDATE tb_financail SET Sales=?,Award=?,AwardNo=?,UserID=? WHERE FinancialID=?";
    $stm = $connect->prepare($sql);
    $sales = str_replace(',', '', $_POST['Sales']);
    $Award = str_replace(',', '', $_POST['Award']);
    $Data = [$sales, $Award, $_POST['Awardno'], $_POST['userID'], $_GET['id']];
    $stm->execute($Data);
    if ($stm) {
        $conn->createJson("success", "ແກ້ໄຂຂໍ້ມູນສຳເລັດ", true);
    } else {
        $conn->createJson("error", "ແກ້ໄຂຂໍ້ມູນຜິດພາດ", false);
    }
}

function cheking()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COUNT(*) AS fnCount FROM tb_financail WHERE UnitID=? AND lotteryID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$_POST['unitID'], $_GET['id']]);
    $result = $stm->fetchAll();
    return $result[0]['fnCount'];
}

function getfinancial()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COUNT(*) AS fnCount FROM tb_financail WHERE UnitID=? AND lotteryID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$_GET['UnitID'], $_GET['id']]);
    $result = $stm->fetchAll();
    $conn->createJson($result[0]['fnCount'] > 0, "ກວດສອບຂໍ້ມູນ", true);
}

function getFinancials()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_financail
    INNER JOIN tb_unit ON tb_financail.UnitID=tb_unit.unitID
    INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID
    INNER JOIN tb_lottery ON tb_financail.lotteryID = tb_lottery.lotteryID
    ORDER BY tb_financail.FinancialID DESC';
    $stm = $connect->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll();
    $conn->createJson($result, "ຂໍ້ມູນທັງໝົດ", true);
}

function getReportFinancials()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_financail
    INNER JOIN tb_unit ON tb_financail.UnitID=tb_unit.unitID
    INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID
    INNER JOIN tb_lottery ON tb_financail.lotteryID = tb_lottery.lotteryID
    WHERE MONTH(tb_lottery.lotDate)=?
    ORDER BY tb_lottery.lotteryNo DESC';
    $stm = $connect->prepare($sql);
    $stm->execute([$_GET['month']]);
    $result = $stm->fetchAll();
    $conn->createJson($result, "ຂໍ້ມູນທັງໝົດ", true);
}

function getfinancialID()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_financail
    INNER JOIN tb_lottery ON tb_financail.lotteryID=tb_lottery.lotteryID
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    WHERE tb_financail.UnitID=? AND tb_financail.lotteryID=?';
    $stm = $connect->prepare($sql);
    $stm->execute([$_GET['unitID'], $_GET['lotteryID']]);
    $result = $stm->fetchAll();
    if ($result) {
        $conn->createJson($result[0], "ຂໍ້ມູນທັງໝົດ", true);
    } else {
        $conn->createJson([], "ບໍ່ພົບຂໍ້ມູນ", false);
    }
}

function getReprotFinancialSearch()
{
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $startDate = $_POST['startdate'];
    $endDate = $_POST['enddate'];
    $provinceID = $_POST['proviceid'] ?? "0";
    $unitID = $_POST['unitid'] ?? "0";
    $sql = 'SELECT *
            FROM tb_financail
            INNER JOIN tb_lottery ON  tb_financail.lotteryID= tb_lottery.lotteryID
            INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID WHERE ';
    $searchData = array();
    try {
        if (empty($startDate) && empty($endDate) && $provinceID == "0" && $unitID == "0") {
            $conn->createJson([], "ວ່າງເປົ່າ", false);
        } else {
            if (!empty($startDate)) {
                $startDate = date('Y-m-d', strtotime($_POST['startdate'] ?? ""));
                array_push($searchData, $startDate);
                $arrLength = count($searchData);
                $useAnd = $arrLength == 1 ? "" : " AND ";
                $sql .= $useAnd . "tb_lottery.lotDate >=?";
            }
            if (!empty($endDate)) {
                $endDate = date('Y-m-d', strtotime($_POST['enddate']) ?? "");
                array_push($searchData, $endDate);
                $arrLength = count($searchData);
                $useAnd = $arrLength == 1 ? "" : " AND ";
                $sql .= $useAnd . "tb_lottery.lotDate <=?";
            }
            if ($provinceID != "0") {
                array_push($searchData, $provinceID);
                $arrLength = count($searchData);
                $useAnd = $arrLength == 1 ? "" : " AND ";
                $sql .= $useAnd . "tb_unit.provinceID=?";
            }
            if ($unitID != "0") {
                array_push($searchData, $unitID);
                $arrLength = count($searchData);
                $useAnd = $arrLength == 1 ? "" : " AND ";
                $sql .= $useAnd . "tb_financail.UnitID =?";
            }
            $stm = $connect->prepare($sql);
            $stm->execute($searchData);
            $result = $stm->fetchAll();
            $conn->createJson($result, "ຄົ້ນຫາຂໍ້ມູນ", true);
        }
    } catch (\Throwable $th) {
        $conn->createJson($th, "errro", true);
    }
}

// $startDate = date('Y-m-d', strtotime($_POST['startdate'] ?? ""));
// $endDate = date('Y-m-d', strtotime($_POST['enddate']) ?? "");
