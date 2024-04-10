<?php
if (isset($_GET['unitid'])) {
    require_once ("./database/connectDB.php");
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
        $sumSale = 0;
        $sumCalpercent = 0;
        $sumAward = 0;
        $sumAmount = 0;
        foreach ($result as $row) {
            $Moneys = getMoney($row);
            $sumSale += $Moneys['Sales'];
            $sumCalpercent += $Moneys['CalPercent'];
            $sumAward += $Moneys['Award'];
            $sumAmount += $Moneys['Amount'];
            ?>
            <tr>
                <td class="text-center">
                    <?= $index++ ?>
                </td>
                <td class="text-center">
                    <?= $row['lotteryNo'] ?>
                </td>
                <td class="text-center">
                    <?= date("d/m/Y", strtotime($row['lotDate'])) ?>
                </td>
                <td class="text-center">
                    <?= $Moneys['Percentage'] ?>%
                </td>
                <td class="text-end">
                    <?= number_format($Moneys['Sales']) ?>
                </td>
                <td class="text-end">
                    <?= number_format($Moneys['CalPercent']) ?>
                </td>
                <td class="text-end">
                    <?= number_format($Moneys['Award']) ?>
                </td>
                <td class="text-end">
                    <?= number_format($Moneys['Amount']) ?>
                </td>
            </tr>
            <?php
        }
        $formatmoney = [number_format($sumSale), number_format($sumCalpercent), number_format($sumAward), number_format($sumAmount)];
        echo "
        <tr>
            <td colspan='4' class='text-center'>ລ່ວມເງິນທັງໝົດ</td>
            <td class='text-end'>$formatmoney[0]</td>
            <td class='text-end'>$formatmoney[1]</td>
            <td class='text-end'>$formatmoney[2]</td>
            <td class='text-end'>$formatmoney[3]</td>
        </tr>";
    } else {
        echo "<tr><td colspan='8' class='text-center'>ບໍ່ພົບຂໍ້ມູນການຖອກເງິນ</td></tr>";
    }
}

function getMoney($row)
{
    $sales = (int) $row['Sales'];
    $percentage = (int) $row['Percentage'];
    $award = (int) $row['Award'];
    $isWithDraw = (int) $row['withdrawn'] == 0;
    $calpercentage = ($sales * $percentage) / 100;
    $total = $sales - $calpercentage;
    $amount = $isWithDraw ? $total : $total - $award;
    return [
        "Sales" => $sales,
        "Percentage" => $percentage,
        "Award" => $award,
        "CalPercent" => $calpercentage,
        "Amount" => $amount
    ];
}
