<?php
if (isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['pid']) && isset($_GET['unitID'])) {
    $startDate = $_GET['startdate'];
    $endDate = $_GET['enddate'];
    $provinceID = $_GET['pid'];
    $unitID = $_GET['unitID'];
    $searchData = array();
    $sqlFinancial = "SELECT tb_financail.UnitID FROM tb_financail INNER JOIN tb_lottery ON  tb_financail.lotteryID= tb_lottery.lotteryID WHERE ";
    if (empty($startDate) && empty($endDate) && $provinceID == "0" && $unitID == "0") {
        CreateTableData([]);
    } else {
        if (!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate ?? ""));
            array_push($searchData, $startDate);
            $arrLength = count($searchData);
            $useAnd = $arrLength == 1 ? "" : " AND ";
            $sqlFinancial .= $useAnd . "tb_lottery.lotDate >=?";
        }
        if (!empty($endDate)) {
            $endDate = date('Y-m-d', strtotime($endDate ?? ""));
            array_push($searchData, $endDate);
            $arrLength = count($searchData);
            $useAnd = $arrLength == 1 ? "" : " AND ";
            $sqlFinancial .= $useAnd . "tb_lottery.lotDate <=?";
        }
        if ($provinceID != "0") {
            array_push($searchData, $provinceID);
            $arrLength = count($searchData);
            $useAnd = $arrLength == 1 ? "" : " AND ";
            $sqlFinancial .= $useAnd . "tb_unit.provinceID=?";
        }
        if ($unitID != "0") {
            array_push($searchData, $unitID);
            $arrLength = count($searchData);
            $useAnd = $arrLength == 1 ? "" : " AND ";
            $sqlFinancial .= $useAnd . "tb_financail.UnitID =?";
        }
        require_once("./database/connectDB.php");
        $connnect = new connectDB();
        $db = $connnect->getConnection();
        $sql = "SELECT * FROM tb_unit WHERE unitID IN ($sqlFinancial)";
        $stmt = $db->prepare($sql);
        $stmt->execute($searchData);
        $result = $stmt->fetchAll();
        CreateTableData($result);
    }
} else {
    CreateTableData([]);
}

function CreateTableData($result)
{
    if (count($result) > 0) {
        $index = 1;
        $totalSales = 0;
        $totalAward = 0;
        $totalPercent = 0;
        $totalFinal = 0;
        foreach ($result as $row) {
            $calMoney = calculator($row['unitID']);
            $totalSales += $calMoney['sumSales'];
            $totalAward += $calMoney['sumAward'];
            $totalPercent += $calMoney['Percentage'];
            $totalFinal += $calMoney['finalTotal'];
?>
            <tr class="text-end">
                <td class="text-center"><?= $index++ ?></td>
                <td class="text-center"><?= $row['unitName'] ?></td>
                <td class="text-center"><?= $row['Percentage'] ?>%</td>
                <td><?= number_format($calMoney['sumSales']) ?></td>
                <td><?= number_format($calMoney['Percentage']) ?></td>
                <td><?= number_format($calMoney['sumAward']) ?></td>
                <td><?= number_format($calMoney['finalTotal']) ?></td>
            </tr>
        <?php
        } //end foreach
        ?>
        <!-- Total row -->
        <tr class="text-end">
            <td colspan="3"></td>
            <td><?= number_format($totalSales) ?></td>
            <td><?= number_format($totalAward) ?></td>
            <td><?= number_format($totalPercent) ?></td>
            <td><?= number_format($totalFinal) ?></td>
        </tr>
<?php
    } else {
        echo "<td colspan='8'>..... ຄົ້ນຫາຂໍ້ມູນການປ້ອນຂໍ້ມູນ .....</td>";
    }
}

function calculator($unitID)
{
    $startDate = $_GET['startdate'];
    $endDate = $_GET['enddate'];
    $searchData = array();
    //create Select
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT 
    SUM(fc.Sales) AS sumSales,
    SUM(fc.Award) AS sumAward, 
    CAST((SUM(fc.Sales) * fc.Percentage) / 100 AS SIGNED) AS total,
    CASE 
        WHEN un.withdrawn = 0 THEN CAST((SUM(fc.Sales) * fc.Percentage) / 100 AS SIGNED)
        ELSE CAST((SUM(fc.Sales) * fc.Percentage) / 100 AS SIGNED) - SUM(fc.Award)
    END AS finalTotal
    FROM 
    tb_financail AS fc
    INNER JOIN tb_unit AS un ON fc.UnitID = un.unitID
    INNER JOIN tb_lottery AS lot ON fc.lotteryID = lot.lotteryID 
    WHERE ";
    if (!empty($startDate)) {
        $startDate = date('Y-m-d', strtotime($startDate ?? ""));
        array_push($searchData, $startDate);
        $arrLength = count($searchData);
        $useAnd = $arrLength == 1 ? "" : " AND ";
        $sql .= $useAnd . "lot.lotDate >=?";
    }
    if (!empty($endDate)) {
        $endDate = date('Y-m-d', strtotime($endDate ?? ""));
        array_push($searchData, $endDate);
        $arrLength = count($searchData);
        $useAnd = $arrLength == 1 ? "" : " AND ";
        $sql .= $useAnd . "lot.lotDate <=?";
    }

    $useAnd = $arrLength == 0 ? "" : " AND ";
    $sql .= $useAnd . " fc.UnitID = ? AND fc.state = 0 GROUP BY fc.UnitID, fc.Percentage";
    $stmt = $db->prepare($sql);
    array_push($searchData, $unitID);
    $stmt->execute($searchData);
    $result = $stmt->fetchAll();
    $data = $result[0];
    return [
        "sumSales" => $data['sumSales'],
        "sumAward" => $data['sumAward'],
        "Percentage" => $data['total'],
        "finalTotal" => $data['finalTotal'],
    ];
}
