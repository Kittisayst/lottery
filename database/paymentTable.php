<?php
if (isset($_GET['search'])) {
    $pid = $_GET['provinceID'];
    $lotno = $_GET['paymentNo'];
    header("Location:/lottery?page=payment&pid=$pid&lotid=$lotno");
}

if (isset($_GET['pid']) && isset($_GET['lotid'])) {
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = 'SELECT * FROM tb_financail
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    INNER JOIN tb_province ON tb_unit.provinceID = tb_province.pid
    INNER JOIN tb_lottery ON tb_financail.lotteryID = tb_lottery.lotteryID
    WHERE tb_unit.provinceID=? AND tb_financail.lotteryID=? ORDER BY tb_unit.unitID';
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['pid'], $_GET['lotid']]);
    $result = $stmt->fetchAll();
    $index = 1;
    if ($result) {
        foreach ($result as $row) {
            $Sale = (int)$row['Sales'];
            $Percentage = (int)$row['Percentage'];
            $calPercent = ($Sale * $Percentage) / 100;
            $award = (int)$row['Award'];
            $total = $Sale - $calPercent - $award;
            $payment = Calculator($row['FinancialID']);
            $Amount = $total - $payment;
            $formatTotal = number_format($Amount);
?>
            <tr id="<?=$row['FinancialID']?>">
                <td class='text-center'><?= $index++ ?></td>
                <td><?= $row['lotteryNo'] ?></td>
                <td><?= $row['pname'] ?></td>
                <td><?= $row['unitName'] ?></td>
                <td><?= $formatTotal ?></td>
            </tr>
<?php
        }
    } else {
        echo '<td colspan="5" class="text-center">---- ບໍ່ພົບຂໍ້ມູນການຖອກເງິນ ----</td>';
    }
} else {
    echo '<td colspan="5" class="text-center">---- ຄົ້ນຫາຂໍ້ມູນການຖອກເງິນ ----</td>';
}

function Calculator($financialID)
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT * FROM tb_payment WHERE FinancialID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$financialID]);
    $result = $stmt->fetchAll();
    if ($result) {
        $sum = 0;
        foreach ($result as $row) {
            $sum += (int)$row['Cash'] + (int)$row['Transfer'] + (int)$row['Etc'];
        }
        return $sum;
    } else {
        return 0;
    }
}
