<?php
require_once ("database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = "SELECT us.userID,us.UserName,us.User,us.log,ps.name FROM tb_user AS us
LEFT JOIN tb_userpermission AS up ON us.userID = up.userID
LEFT JOIN tb_permission AS ps ON up.permissionID = ps.permissionID";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$index = 0;
foreach ($result as $row) {
    $index++;
    $id = $row['userID'];
    $permissionName = isset($row['name']) ? $row['name'] : "<span class='text-warning'>ບໍ່ມີສິດທິ</span>";
    echo "
            <tr class='text-center'>
                <td>$index</td>
                <td>" . $row['UserName'] . "</td>
                <td>" . $row['User'] . "</td>
                <td id='u$id'>" . $permissionName . "</td>
                <td>" . $row['log'] . "</td>
                <td>
                <button class='btn btn-warning btn-sm' onclick=addPermission($id)>ສິດທິ</button>
                    <button class='btn btn-success btn-sm' onclick=edit($id)>ແກ້ໄຂ</button>
                    <button class='btn btn-danger btn-sm'>ລົບ</button>
                </td>
            </tr>";
}