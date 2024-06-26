<?php

require_once ("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = "SELECT * FROM tb_save_pdf_data AS spdf
INNER JOIN tb_unit ON spdf.unitID = tb_unit.unitID
INNER JOIN tb_province AS pv ON tb_unit.provinceID = pv.pid
INNER JOIN tb_lottery AS lot ON spdf.lotteryID = lot.lotteryID
ORDER BY savePDF_ID DESC";
$parmdata = [];
if (isset($_GET['lotid'])) {
    $sql = "SELECT * FROM tb_save_pdf_data AS spdf
    INNER JOIN tb_unit ON spdf.unitID = tb_unit.unitID
    INNER JOIN tb_province AS pv ON tb_unit.provinceID = pv.pid
    INNER JOIN tb_lottery AS lot ON spdf.lotteryID = lot.lotteryID
    WHERE lot.lotteryID=?
    ORDER BY savePDF_ID DESC";
    $parmdata = [$_GET['lotid']];
}
$stmt = $db->prepare($sql);

$stmt->execute($parmdata);


$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($result) > 0) {
    $index = 0;
    foreach ($result as $row) {
        // ຍອດຂາຍ ແຂວງ​ໄຊຍະບູລີ ໜ່ວຍ​ ທ.​ເປ​ ວັນທີ່.​ 19/04/2024
        $text = "ຍອດຂາຍ " . $row['pname'] . " ໜ່ວຍ" . $row['unitName'] . "ວັນທີ່." . date("d/m/Y", strtotime($row['lotDate']));
        $comment = $row['comment'];
        $id = $row['savePDF_ID'];
        $isshowSave = checkIsSaveFinancial($row['unitID'], $row['lotteryID']);
        if (!$isshowSave) {
            $index++;
            echo "
            <tr class='text-center'>
                <td>$index</td>
                <td>$text</td>
                <td>$comment</td>
                <td class='d-flex gap-2'>
                    <button class='btn btn-primary btn-sm w-50' onclick='saveFinancial($id)' $isshowSave><i class='bi bi-floppy2-fill'></i></button>
                    <a href='?page=printsalepdf&id=$id' class='btn btn-info btn-sm w-50'><i class='bi bi-eye-fill'></i></a>
                    <button class='btn btn-danger btn-sm w-50'><i class='bi bi-trash-fill'></i></button>
                </td>
            </tr>";
        }
    }
} else {
    echo "
    <tr class='text-center'>
        <td colspan='4'>ບໍ່ພົບຂໍ້ມູນ PDF ການຂາຍ</td>
    </tr>";
}

function checkIsSaveFinancial($unitID, $loteryID)
{
    require_once ("./database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT COUNT(*) AS fnCount FROM tb_financail WHERE UnitID=? AND lotteryID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$unitID, $loteryID]);
    $result = $stm->fetchAll();
    return $result[0]['fnCount'] > 0;
}


