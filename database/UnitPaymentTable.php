<?php
if (isset($_GET['pid'])) {
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $data = [];
    $searchParm = isset($_GET['search']) ? $_GET['search'] : "";
    if (isset($_GET['search'])) {
        $sql = 'SELECT * FROM tb_unit WHERE provinceID=? AND unitName LIKE ?';
        $search = "%" . $_GET['search'] . "%";
        $data = [$_GET['pid'], $search];
    } else {
        $sql = 'SELECT * FROM tb_unit WHERE provinceID=?';
        $data = [$_GET['pid']];
    }
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    $result = $stmt->fetchAll();
    $index = 1;
    foreach ($result as $row) {
?>
        <tr class="text-center">
            <td><?= $index++ ?></td>
            <td><?= $row['unitName'] ?></td>
            <td><?=getArrears($row['unitID'])?></td>
            <td class="col-2">
                <a href="?page=history&unitid=<?= $row['unitID'] ?>&pid=<?= $_GET['pid'] ?>&search=<?= $searchParm ?>" class="btn btn-info btn-sm"><i class='bx bx-history'></i> ປະຫວັດ</a>
                <a href="?page=listpayment&unitid=<?= $row['unitID'] ?>&pid=<?= $_GET['pid'] ?>&search=<?= $searchParm ?>" class="btn btn-warning btn-sm"><i class='bx bxs-dollar-circle'></i> ຖອກເງິນ</a>
            </td>
        </tr>
<?php
    }
} else {
    echo "<tr><td colspan='4' class='text-center'>ບໍ່ພົບຂໍ້ມູນໜ່ວຍ</td></tr>";
}

function getArrears($unitID)
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT COALESCE(COUNT(FinancialID),0) AS Arrears FROM tb_financail WHERE UnitID=? AND state=0";
    $stmt = $db->prepare($sql);
    $stmt->execute([$unitID]);
    $result = $stmt->fetchAll();
    return $result[0]['Arrears'];
}
