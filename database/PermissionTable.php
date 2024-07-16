<?php

require_once ("./database/connectDB.php");
$sql = "SELECT * FROM tb_permission";
$connnect = new connectDB();
$db = $connnect->getConnection();
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$index = 1;
foreach ($result as $permission) {
    ?>
    <tr class="text-center">
        <td><?= $index++ ?></td>
        <td><?= $permission['name'] ?></td>
        <td class="col-1">
            <a class="btn btn-success" href="?page=updatePermission&id=<?=$permission['permissionID']?>"><i class="bi bi-tools"></i></a>
        </td>
    </tr>
    <?php
}