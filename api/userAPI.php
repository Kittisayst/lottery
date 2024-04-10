<?php
header('Content-Type: application/json');

if (isset ($_GET['api'])) {
    $api = $_GET['api'];
    switch ($api) {
        case 'getusers':
            getUsers();
            break;
        case 'getlogin':
            $user = $_POST['txtuser'];
            $password = $_POST['password'];
            getLogin($user, $password);
            break;
        case 'getlogout':
            getlogout();
            break;
        case 'create':
            create();
            break;
        case 'update':
            update();
            break;
        case 'delete':
            delete();
            break;
        default:
            # code...
            break;
    }
}

function createUser(array $users)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "INSERT INTO tb_user VALUES(?,?,?,?,?)";
    $stm = $connect->prepare($sql);
    return $stm->execute($users);
}

function getLogin($user, $password)
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_user WHERE User=? AND Password=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$user, $password]);
    $result = $stm->fetchAll();
    if ($result) {
        $expiration_time = time() + 3600;
        // Set the cookie with the calculated expiration time
        setcookie("user", $result[0]['userID'], $expiration_time, "/");
        $conn->createJson($result[0]['userID'], "ເຂົ້າສູ່ລະບົບສຳເລັດ", true);
    } else {
        $conn->createJson($result, "ຊື່ຜູ້ໃຊ້ງານ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ!", false);
    }
}

function getlogout()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    setcookie("user", '55', time() - 3600, "/");
    $conn->createJson(0, "logout", true);
}

function getUsers()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "SELECT * FROM tb_user";
    $stm = $connect->prepare($sql);
    $stm->execute();
    $conn->createJson($stm->fetchAll(), "ຂໍ້ມູນຜູ້ໃຊ້ງານ", true);
}

function create()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "INSERT INTO tb_user VALUES(?,?,?,?,?)";
    $stm = $connect->prepare($sql);
    $Data = [null, $_POST['UserName'], $_POST['User'], $_POST['Password'], '00:00:00'];
    $stm->execute($Data);
    if ($stm) {
        $conn->createJson('', "ເພີ່ມຂໍ້ມູນຜູ້ໃຊ້ງານສຳເລັດ", true);
    } else {
        $conn->createJson('', "ເພີ່ມຂໍ້ມູນຜູ້ໃຊ້ງານຜິດພາດ", false);
    }
}

function update()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    if ($_POST['Password'] != "") {
        $sql = "UPDATE tb_user SET UserName=?,User=?,Password=? WHERE userID=?";
        $stm = $connect->prepare($sql);
        $Data = [$_POST['UserName'], $_POST['User'], $_POST['Password'], $_GET['id']];
    } else {
        $sql = "UPDATE tb_user SET UserName=?,User=? WHERE userID=?";
        $stm = $connect->prepare($sql);
        $Data = [$_POST['UserName'], $_POST['User'], $_GET['id']];
    }
    $stm->execute($Data);
    if ($stm) {
        $conn->createJson('', "ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້ງານສຳເລັດ", true);
    } else {
        $conn->createJson('', "ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້ງານຜິດພາດ", false);
    }
}

function delete()
{
    require_once ("../database/connectDB.php");
    $conn = new connectDB();
    $connect = $conn->getConnection();
    $sql = "DELETE FROM tb_user WHERE userID=?";
    $stm = $connect->prepare($sql);
    $stm->execute([$_GET['id']]);
    if ($stm) {
        $conn->createJson('', "ລົບຂໍ້ມູນຜູ້ໃຊ້ງານສຳເລັດ", true);
    } else {
        $conn->createJson('', "ລົບຂໍ້ມູນຜູ້ໃຊ້ງານຜິດພາດ", false);
    }
}
