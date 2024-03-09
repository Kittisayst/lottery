<?php
if (isset($_GET['unitid'])) {
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT * FROM tb_financail
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    INNER JOIN tb_lottery ON tb_financail.lotteryID = tb_lottery.lotteryID
    WHERE tb_unit.unitID = ? AND state=0 ORDER BY FinancialID";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['unitid']]);
    $result = $stmt->fetchAll();
    $state = $stmt->rowCount() > 0;
    $index = 1;
    if ($state) {
        foreach ($result as $row) {
            $Moneys = getMoney($row);
?>
            <tr>
                <td class="text-center"><?= $index++ ?></td>
                <td class="text-center"><?= $row['lotteryNo'] ?></td>
                <td class="text-center"><?= date("d/m/Y", strtotime($row['lotDate'])) ?></td>
                <td class="text-end"><?= $Moneys['Sales'] ?></td>
                <td class="text-center"><?= $Moneys['Percentage'] ?>%</td>
                <td class="text-end"><?= $Moneys['CalPercent'] ?></td>
                <td class="text-end"><?= $Moneys['Award'] ?></td>
                <td class="text-end"><?= $Moneys['Amount'] ?></td>
                <td class="text-center col-1">
                    <button class="btn btn-sm btn-primary"><i class='bx bx-file'></i> ໃບທວງໜີ້</button>
                </td>
            </tr>
<?php
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>ບໍ່ພົບຂໍ້ມູນການຖອກເງິນ</td></tr>";
    }
}

function getMoney($row)
{
    $sales = (int)$row['Sales'];
    $percentage = (int)$row['Percentage'];
    $award = (int)$row['Award'];
    $isWithDraw = (int)$row['withdrawn'] == 0;
    $calpercentage = ($sales * $percentage) / 100;
    $total = $sales - $calpercentage;
    $amount = $isWithDraw ? $total : $total - $award;
    return [
        "Sales" => number_format($sales),
        "Percentage" => $percentage,
        "Award" => number_format($award),
        "CalPercent" => number_format($calpercentage),
        "Amount" => number_format($amount)
    ];
}
