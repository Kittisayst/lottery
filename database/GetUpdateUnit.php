<?php
$isUpdateUnit = false;
if (isset($_GET['id'])) {
    $isUpdateUnit = true;
    $id = $_GET['id'];
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $stmt = $db->prepare("SELECT * FROM `tb_unit` WHERE unitID=?");
    $stmt->execute([$id]);
    $result = $stmt->fetchAll();
    if ($result) {
        $productID = $result[0]['provinceID'];
        $unitResult = $result[0];
        $stmoption = $db->prepare("SELECT * FROM tb_province");
        $stmoption->execute();
        $options = $stmoption->fetchAll();
        $stroption = "";
        $stroption .= "<option value='' disabled selected>---ເລືອກແຂວງ---</option>";
        foreach ($options as $option) {
            $isselect = $option['pid'] == $productID ? 'selected' : '';
            $stroption .= "<option value=".$option['pid']." ".$isselect.">".$option['pname']."</option>";
        }
    }
}
