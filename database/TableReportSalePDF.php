<?php

require_once ("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = "SELECT * FROM tb_save_pdf_data AS spdf
INNER JOIN tb_unit ON spdf.unitID = tb_unit.unitID
INNER JOIN tb_province AS pv ON tb_unit.provinceID = pv.pid
INNER JOIN tb_lottery AS lot ON spdf.lotteryID = lot.lotteryID
ORDER BY savePDF_ID DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($result) > 0) {
    $index = 0;
    foreach ($result as $row) {
        $index++;
        // ຍອດຂາຍ ແຂວງ​ໄຊຍະບູລີ ໜ່ວຍ​ ທ.​ເປ​ ວັນທີ່.​ 19/04/2024
        $text = "ຍອດຂາຍ " . $row['pname'] . " ໜ່ວຍ" . $row['unitName'] . "ວັນທີ່." . date("d/m/Y", strtotime($row['lotDate']));
        $comment = $row['comment'];
        $id = $row['savePDF_ID'];
        echo "
        <tr class='text-center'>
            <td>$index</td>
            <td>$text</td>
            <td>$comment</td>
            <td class='d-flex gap-2'>
                <a href='?page=printsalepdf&id=$id' class='btn btn-info btn-sm w-50'><i class='bi bi-eye-fill'></i></a>
                <button class='btn btn-danger btn-sm w-50'><i class='bi bi-trash-fill'></i></button>
            </td>
        </tr>";
    }
} else {
    echo "no";
}


