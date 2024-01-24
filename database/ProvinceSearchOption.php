<?php
require_once("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$stmt = $db->prepare("SELECT * FROM tb_province");
$stmt->execute();
$result = $stmt->fetchAll();
if ($result) {
    echo "<option value='0' selected>---ແຂວງທັງໝົດ---</option>";
    foreach ($result as $row) {
        echo "<option value=" . $row['pid'] .">" . $row['pname'] . "</option>";
    }
} else {
    echo "<option value='0' selected>---ແຂວງທັງໝົດ---</option>";
}
