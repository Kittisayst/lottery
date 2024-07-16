<?php
require_once ("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = "SELECT salePDFID,tb_salepdflaolot.lotteryID,tb_salepdflaolot.lotteryNo AS pdflotno,tb_lottery.lotteryNo AS lotno,tb_salepdflaolot.lotDate,FileName,
fileSize,pdfData,UserID FROM tb_salepdflaolot
INNER JOIN tb_lottery ON tb_salepdflaolot.lotteryID = tb_lottery.lotteryID ORDER BY tb_salepdflaolot.lotteryNo DESC LIMIT 35";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$html = "";
$index = 0;
// var_dump($result);
foreach ($result as $row) {
    $index++;
    $salePDFID = $row['salePDFID'];
    $lotteryNo = $row['pdflotno'];
    $lotDate = date("d/m/Y", strtotime($row['lotDate']));
    $FileName = $row['FileName'];
    $fileSize = $row['fileSize'];
    $lotNo = $row['lotno'];
    $salePDFID  = $row['salePDFID'];
    $html .= "
    <tr class='text-center'>
        <td>$index</td>
        <td>$lotNo</td>
        <td>$lotteryNo</td>
        <td>$lotDate</td>
        <td>$FileName</td>
        <td>$fileSize KB</td>
        <td class='d-flex gap-2'>
            <button class='btn btn-sm btn-primary' onclick='handelEdit($salePDFID)'><i class='bi bi-pencil-square'></i> ແກ້ໄຂ</button>
            <a href='?page=scanpayment&id=$salePDFID&limit=100&pagination=1' class='btn btn-success btn-sm col'>
                <i class='bi bi-eye-fill'></i> ສະແດງ
            </a>
            <button class='btn btn-danger btn-sm col' onclick='deletePDF($salePDFID)'>
                <i class='bi bi-trash3-fill'></i > ລົບ
            </button>
        </td>
    </tr>
    ";
}
echo $html;