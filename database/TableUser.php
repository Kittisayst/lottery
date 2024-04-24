<?php
if (isset($_GET['api'])) {
    require_once ("../database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    if ($_GET['api'] == "users") {
        $stmt = $db->prepare("SELECT * FROM tb_user");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $index = 0;
        foreach ($result as $row) {
            $index++;
            $id = $row['userID'];
            echo "
            <tr class='text-center'>
                <td>$index</td>
                <td>" . $row['UserName'] . "</td>
                <td>" . $row['User'] . "</td>
                <td>" . $row['log'] . "</td>
                <td>
                    <button class='btn btn-success btn-sm' onclick=edit($id)>ແກ້ໄຂ</button>
                    <button class='btn btn-danger btn-sm'>ລົບ</button>
                </td>
            </tr>";
        }
    }
}

?>


// $encode = json_encode($result);
// echo $encode;