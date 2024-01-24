<?php
require_once("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$stmt = $db->prepare("SELECT * FROM tb_unit");
$stmt->execute();
$result = $stmt->fetchAll();
if ($result) {
    echo "<option value='0' selected>---ໜ່ວຍທັງໝົດ---</option>";
    foreach ($result as $row) {
        echo "<option value=" . $row['unitID'] .">" . $row['unitName'] . "</option>";
    }
} else {
    echo "<option value='0'>---ໜ່ວຍທັງໝົດ---</option>";
}
