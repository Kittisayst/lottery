<?php
require_once("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$stmt = $db->prepare("SELECT * FROM tb_province");
$stmt->execute();
$result = $stmt->fetchAll();
if ($result) {
    echo "<option value='0' selected>---ເລືອກແຂວງ---</option>";
    foreach ($result as $row) {
        $isselect = "";
        if (isset($_GET['pid'])) {
            $pid = $_GET['pid'];
            $isselect = $row['pid'] == $pid ? " selected" : "";
        }
        echo "<option value=" . $row['pid'] . " " . $isselect . ">" . $row['pname'] . "</option>";
    }
} else {
    echo "<option value=''>---ເລືອກແຂວງ---</option>";
}
