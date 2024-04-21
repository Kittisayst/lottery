<?php
require_once ("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = "SELECT * FROM tb_salepdf ORDER BY salePDFID DESC LIMIT 35";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$html = "";
$index = 0;
// var_dump($result);
foreach ($result as $row) {
    $index++;
    $salePDFID = $row['salePDFID'];
    $lotteryNo = $row['lotteryNo'];
    $lotDate = date("d/m/Y", strtotime($row['lotDate']));
    $FileName = $row['FileName'];
    $fileSize = $row['fileSize'];
    $html .= "
    <tr class='text-center'>
        <td>$index</td>
        <td>$lotteryNo</td>
        <td>$lotDate</td>
        <td>$FileName</td>
        <td>$fileSize KB</td>
        <td class='d-flex gap-2'>
            <a href='?page=scanpayment&id=$salePDFID&limit=100&pagination=1' class='btn btn-primary btn-sm col'>ສະແດງ</a>
            <button class='btn btn-danger btn-sm col'>ລົບ</button>
        </td>
    </tr>
    ";
}
echo $html;