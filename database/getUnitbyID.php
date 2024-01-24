<?php
function getUnitData()
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $stmt = $db->prepare("SELECT * FROM tb_unit WHERE unitID=?");
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll();
    $unitData = $result[0];
    return $unitData;
}

function getProvinceOption()
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $stmt = $db->prepare("SELECT * FROM tb_province");
    $stmt->execute();
    $result = $stmt->fetchAll();
    if ($result) {
        echo "<option value='' disabled selected>---ເລືອກແຂວງ---</option>";
        foreach ($result as $row) {
            $isProvince =  $row['pid'] == getUnitData()['provinceID'];
            $selected = $isProvince ? " selected" : "";
            echo "<option value=" . $row['pid'] . " ".$selected.">" . $row['pname'] . "</option>";
        }
    } else {
        echo "<option value=''>---ເລືອກແຂວງ---</option>";
       
    }
}
