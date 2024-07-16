<?php
header('Content-Type: application/json');
require_once ("../database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = "SELECT JSON_EXTRACT(pdfData,'$') AS result FROM tb_salepdflaolot WHERE salePDFID =?";
$data = [$_GET['id']];
if (isset($_GET['unitID'])) {
    $sql = "SELECT JSON_ARRAYAGG(json_obj) AS result
  FROM (
      SELECT json_obj
      FROM tb_salepdflaolot,
           JSON_TABLE(pdfData, '$[*]' COLUMNS (
               json_obj JSON PATH '$'
           )) AS j
      WHERE JSON_UNQUOTE(JSON_EXTRACT(json_obj, '$.machineCode')) IN 
      (SELECT tb_machinelotlink.machineCode FROM tb_machinelotlink WHERE tb_machinelotlink.UnitID=?)
      AND tb_salepdflaolot.salePDFID=?
  ) AS filtered_data";
    $data = [$_GET['unitID'], $_GET['id']];
}
$stmt = $db->prepare($sql);
$stmt->execute($data);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$Saledata = $result[0]['result'];
$connnect->createJson($Saledata, "ຂໍ້ມູນການຂາຍ", true);


