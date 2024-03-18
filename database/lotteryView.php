<?php
require_once("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
if (isset($_GET['datelot'])) {
    if (empty($_GET['datelot'])) {
        $sql = "SELECT * FROM tb_lottery ORDER BY lotteryNo DESC limit 32";
    } else {
        $date = $_GET['datelot'];
        $str = "01-$date";
        $startDate = date('Y-m-d', strtotime($str ?? ""));
        $sql = "SELECT * FROM tb_lottery WHERE lotDate BETWEEN '$startDate' AND LAST_DAY('$startDate') ORDER BY lotteryNo DESC limit 16";
    }
} else {
    $sql = "SELECT * FROM tb_lottery ORDER BY lotteryNo DESC limit 32";
}
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) {
?>
    <div class="border rounded-2 text-center bg-primary col-12 col-xl-2">
        <a href="?page=financial&lotid=<?= $row['lotteryID'] ?>" class="nav-link py-4 text-white d-flex flex-column">
            <span class="fs-5"><?= $row['lotteryNo'] ?></span>
            <span><?= date("d/m/Y", strtotime($row['lotDate'])) ?></span>
        </a>
    </div>
<?php
}
?>