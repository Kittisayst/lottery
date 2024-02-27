<?php
require_once("./router/routes.php");
require_once("./controller/loginController.php");
include_once("./router/startRoute.php");

// Start a new route
$login = new LoginController();
if ($login->isLogin()) {
    include_once("./views/Navbar.php");
    new Router('home', './views/Home.php', 'ລະບົບໂຮງຫວຍ');
    new Router('unit', './views/Unit.php', "ໜ່ວຍແຕ່ລະແຂວງ");
    new Router("dataUnit", './views/DataUnit.php', "ຂໍ້ມູນໜ່ວຍ");
    new Router("financial", "./views/Financial.php", "ປ້ອນຂໍ້ມູນ");
    new Router("inputFinancial", "./views/InputFinancial.php", "ປ້ອນຂໍ້ມູນ");
    new Router("lottery", "./views/Lottery.php", "ຂໍ້ມູນເລກທີ");
    new Router("user", "./views/User.php", "ຂໍ້ມູນຜູ້ໃຊ້ງານ");
    new Router("province", "./views/Province.php", "ຂໍ້ມູນແຂວງ");
    // formInput
    //unit
    new Router("formUnit", "./views/form/unit_form.php", "ເພີ່ມຂໍ້ມູນໜ່ວຍ");
    new Router("editunit", "./views/form/edit_unit_form.php", "ແກ້ໄຂຂໍ້ມູນໜ່ວຍ");
    //user
    new Router("createuser", "./views/form/user_form.php", "ເພີ່ມຜູ້ໃຊ້ງານ");
    new Router("edituser", "./views/form/edit_user_form.php", "ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້ງານ");
    //select lottery
    new Router("selectlot", "./views/selectLottery.php", "ປ້ອນຂໍ້ມູນງວດທີ");
    //Payment
    new Router("payment", "./views/payment.php", "ເລືອກໜ່ວຍຖອກເງິນ");
    new Router("listpayment", "./views/listPayment.php", "ລາຍການຖອກເງິນ");
    //Reports
    new Router("reportfinancial", "./views/report/reportFinancial.php", "ລາຍງານການປ້ອນຂໍ້ມູນ");
    //footer
    require_once("./views/Footer.php");
} else {
    $login->view();
}
