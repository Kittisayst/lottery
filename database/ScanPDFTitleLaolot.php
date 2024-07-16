<?php
function showSalePDFTitle()
{
    if (isset($_GET['id'])) {
        require_once ("./database/connectDB.php");
        $connnect = new connectDB();
        $db = $connnect->getConnection();
        $sql = "SELECT * FROM tb_salepdflaolot WHERE salePDFID=?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$_GET['id']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $result[0];
        $lotteryNo = $data['lotteryNo'];
        $lotDate = date("d/m/Y", strtotime($data['lotDate']));
        $html = "<div class='d-flex w-100 gap-5'>
        <span class='fs-5'>ງວດທີ: <span id='lot'>$lotteryNo</span></span>
        <span class='fs-5'>ວັນທີ: <span id='lotdate'>$lotDate</span></span>
        </div>";
        echo $html;
    } else {
        echo "<div>ຜິດພາບໍ່ພົບ ID ການສະແກນ PDF ການຂາຍ</div>";
    }
}

