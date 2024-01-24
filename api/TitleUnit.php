<?php
if (isset($_GET['unitID'])) {
    $unitID = $_GET['unitID'];
    require_once("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = 'SELECT * FROM tb_unit
    INNER JOIN tb_province ON tb_province.pid = tb_unit.provinceID WHERE tb_unit.unitID =?';
    $stm = $connect->prepare($sql);
    $stm->execute([$unitID]);
    $result = $stm->fetchAll();
    echo $result[0];
}
