<?php
require_once("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$stmt = $db->prepare("SELECT * FROM tb_financail ORDER BY UnitID DESC");
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) {
?>
    <div class="border rounded-2 text-center bg-primary col-2">
        <a href="?page=financial&lotid=<?=$row['lotteryID']?>" class="nav-link py-4 text-white d-flex flex-column">
            <span class="fs-5"><?= $row['lotteryNo'] ?></span>
            <span><?=date("d/m/Y",strtotime($row['lotDate']))?></span>
        </a>
    </div>
<?php
}
?>