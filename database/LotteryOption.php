<?php
require_once("./database/connectDB.php");
$conn = new connectDB();
$connect = $conn->getConnection();
$sql = "SELECT * FROM tb_lottery ORDER BY lotteryNo DESC LIMIT 31";
$stmt = $connect->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
if ($result) {
    $str = '<option value="" disabled selected class="text-secondary">---ເລືອກງວດ---</option>';
    foreach ($result as $row) {
        $isselect = "";
        if (isset($_GET['lotid'])) {
            $lotid = $_GET['lotid'];
            $isselect = $lotid == $row['lotteryID'] ? " selected" : "";
        }
        $valueID = $row['lotteryID'];
        $value = $row['lotteryNo'];
        $str .= "<option value='$valueID' $isselect>$value</option>";
    }
    echo $str;
} else {
    echo '<option value="" disabled selected class="text-secondary">---- ເລືອກງວດ ----</option>';
}
