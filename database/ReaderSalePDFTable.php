<?php
header('Content-Type: application/json');
require_once ("../database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = "SELECT JSON_EXTRACT(pdfData,'$') AS result FROM tb_salepdf WHERE salePDFID =?";
$data = [$_GET['id']];
if (isset($_GET['unitID'])) {
    $sql = "SELECT JSON_ARRAYAGG(json_obj) AS result
  FROM (
      SELECT json_obj
      FROM tb_salepdf,
           JSON_TABLE(pdfData, '$[*]' COLUMNS (
               json_obj JSON PATH '$'
           )) AS j
      WHERE JSON_UNQUOTE(JSON_EXTRACT(json_obj, '$.machineCode')) IN (SELECT tb_machine.machineCode FROM tb_machine WHERE tb_machine.UnitID=?)
      AND tb_salepdf.salePDFID=?
  ) AS filtered_data";
    $data = [$_GET['unitID'], $_GET['id']];
}
$stmt = $db->prepare($sql);
$stmt->execute($data);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$Saledata = $result[0]['result'];
$connnect->createJson($Saledata, "ຂໍ້ມູນການຂາຍ", true);


