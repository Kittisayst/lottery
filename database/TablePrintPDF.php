<?php

if (isset($_GET['id'])) {
    require_once ("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT * FROM tb_save_pdf_data AS spdf
        INNER JOIN tb_unit ON spdf.unitID = tb_unit.unitID
        INNER JOIN tb_province AS pv ON tb_unit.provinceID = pv.pid
        INNER JOIN tb_lottery AS lot ON spdf.lotteryID = lot.lotteryID
        WHERE savePDF_ID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = $result[0];
    $arrdata = json_decode($data['pdfData'], true);
    if (!empty($arrdata)) {
        $index = 0;
        $html = "";
        $sumSale = 0;
        $sumAward = 0;
        $sumPrice = 0;
        $sumAmount = 0;
        foreach ($arrdata as $lot) {
            if (isset($_GET['unitID'])) {
                $lot = json_decode($lot, true);
            }
            $index++;
            $code = $lot['machineCode'];
            $sale = $lot['Sales'];
            $award = $lot['Award'];
            $percent = $lot['Percentage'];
            $price = $lot['Price'];
            $amount = $lot['Amount'];
            echo "
    <tr class='text-end'>
        <td class='text-center'>$index</td>
        <td class='text-center'>$code</td>
        <td>$sale</td>
        <td>$award</td>
        <td class='text-center col-1'>$percent</td>
        <td class='col-1'>$price</td>
        <td>$amount</td>
    </tr>";
            $sum = sumMoney($lot);
            $sumSale += $sum['Sales'];
            $sumAward += $sum['Award'];
            $sumPrice += $sum['Price'];
            $sumAmount += $sum['Amount'];
        }
        echo "<tr class='text-end'>
            <td colspan='2' class='text-center'>ລ່ວມທັງໝົດ</td>
            <td>" . number_format($sumSale) . "</td>
            <td>" . number_format($sumAward) . "</td>
            <td class='text-center'>-</td>
            <td>" . number_format($sumPrice) . "</td>
            <td>" . number_format($sumAmount) . "</td>
        </tr>";
        //ສະແດງຫົວຂໍ້
        showTitle($data, number_format($sumSale), count($arrdata));
    } else {
        echo "<tr class='text-center'><td colspan='7'>...........ບໍ່ພົວຂໍ້ມູນທີ່ກົງກັນ...............</td></tr>";
        echo "<script>$('#btnshow').prop('disabled', false);</script>";
    }
} else {
    echo "<div>ຜິດພາບໍ່ພົບ ID ການສະແກນ PDF ການຂາຍ</div>";
}

function showTitle($data, $sumPrice = 0, $Count = 0)
{
    $pname = $data['pname'];
    $uname = $data['unitName'];
    $lotno = $data['lotteryNo'];
    $lotdate = date("d/m/Y", strtotime($data['lotDate']));
    $lotcorrect = $data['lotteryCorrect'];
    $lotoffline = countMacOffline($data['unitID']) - $Count;
    echo "
    <script>
        $('#pname').text('$pname');
        $('#uname').text('$uname');
        $('#lotno').text('$lotno');
        $('#lotdate').text('$lotdate');
        $('#lotcorrect').text('$lotcorrect');
        $('#sales').text('$sumPrice ກີບ');
        $('#macOnline').text('$Count ເຄື່ອງ');
        $('#macOffline').text('$lotoffline ເຄື່ອງ');
    </script>";
}

function countMacOffline($unitID)
{
    require_once ("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT COALESCE(COUNT(machineID),0) AS macCount FROM tb_machine WHERE UnitID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$unitID]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = $result[0];
    return (int) $data['macCount'];
}

function sumMoney($lot)
{
    $sale = $lot['Sales'];
    $award = $lot['Award'];
    $price = $lot['Price'];
    $amount = $lot['Amount'];
    return ["Sales" => toInt($sale), "Award" => toInt($award), "Price" => toInt($price), "Amount" => toInt($amount)];
}

function toInt($php_string)
{
    // Remove commas from the string
    $number_without_commas = str_replace(",", "", $php_string);

    // Convert the string to an integer
    return (int) $number_without_commas;
}
