<?php
require_once("./database/connectDB.php");
$conn = new connectDB();
$connect = $conn->getConnection();

$isStartDate = isset($_GET['startdate']);
$isStopDate = isset($_GET['enddate']);
$isProviceID = isset($_GET['pid']);
$isUnitID = isset($_GET['unitID']);

if ($isStartDate && $isStopDate && $isProviceID && $isUnitID) {
    $startDate = $_GET['startdate'];
    $endDate = $_GET['enddate'];
    $provinceID = $_GET['pid'] ?? "0";
    $unitID = $_GET['unitID'] ?? "0";
    $searchData = array();
    $sql = "SELECT * FROM tb_paymentlist
    INNER JOIN tb_payment ON tb_paymentlist.paymentID = tb_payment.paymentID
    INNER JOIN tb_financail ON tb_paymentlist.FinancialID = tb_financail.FinancialID
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID WHERE ";
    if (!empty($startDate)) {
        $startvalue = date('Y-m-d', strtotime($startDate) ?? "");
        array_push($searchData, $startvalue);
        $arrLength = count($searchData);
        $useAnd = $arrLength == 1 ? "" : " AND ";
        $sql .= $useAnd . " tb_payment.SaveDate >=?";
    }
    if (!empty($endDate)) {
        $endvalue = date('Y-m-d', strtotime($endDate) ?? "");
        array_push($searchData, $endvalue);
        $arrLength = count($searchData);
        $useAnd = $arrLength == 1 ? "" : " AND ";
        $sql .= $useAnd . " tb_payment.SaveDate <=?";
    }
    if ($provinceID != "0") {
        array_push($searchData, $provinceID);
        $arrLength = count($searchData);
        $useAnd = $arrLength == 1 ? "" : " AND ";
        $sql .= $useAnd . " tb_unit.provinceID=?";
    }
    if ($unitID != "0") {
        array_push($searchData, $unitID);
        $arrLength = count($searchData);
        $useAnd = $arrLength == 1 ? "" : " AND ";
        $sql .= $useAnd . " tb_financail.UnitID =?";
    }
    $sql .= " ORDER BY PaylistID DESC";
    $stmt = $connect->prepare($sql);
    $stmt->execute($searchData);
    $result = $stmt->fetchAll();
    $sum = 0;
    $munisMoney = 0;

    $index = 1;
    if ($stmt->rowCount() > 0) {
        foreach ($result as $row) {
            $Moneys = getMoneys($row);
            $sum += $Moneys['sum'];
            $munisMoney += $Moneys['munisMoney'];
?>
            <tr>
                <td class="text-center"><?= $index++ ?></td>
                <td class="text-center">KF<?= $row['paymentID'] ?></td>
                <td class="text-center"><?= $row['unitName'] ?></td>
                <td class="text-end"><?= $Moneys['cash'] ?></td>
                <td class="text-end"><?= $Moneys['transfer'] ?></td>
                <td class="text-end"><?= $Moneys['repay'] ?></td>
                <td class="text-end"><?= $Moneys['etc'] ?></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="7" class="text-end">
                <span class="text-success">ລວມເງິນທັງໝົດ: <?= number_format($sum) ?> ກີບ</span>
                <span class="text-danger">ບໍລິສັດຕິດລົບ: <?= number_format($munisMoney) ?> ກີບ</span>
            </td>
        </tr>
<?php
    } else {
        echo "<tr class='text-center'><td colspan='7'>.... ເລືອກຂໍ້ມູນການຖອກເງິນ ....</td></tr>";
    }
} else {
    echo "<tr class='text-center'><td colspan='7'>.... ເລືອກຂໍ້ມູນການຖອກເງິນ ....</td></tr>";
}

function getMoneys($row)
{
    $cash = (int)$row['cash'];
    $transfer = (int)$row['transfer'];
    $repay = (int)$row['repay'];
    $etc = (int)$row['etc'];
    $sum = ($cash + $transfer + $etc) - $repay;
    return [
        "cash" => number_format($cash),
        "transfer" => number_format($transfer),
        "repay" => number_format($repay),
        "etc" => number_format($etc),
        "sum" => $sum,
        "munisMoney" => $repay
    ];
}
