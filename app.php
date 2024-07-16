<?php
require_once ("./router/routes.php");
require_once ("./controller/loginController.php"); // use start cookie
require_once ("./router/startRoute.php");
require_once "./database/StartPermission.php";

// Start a new route
$login = new LoginController();

if ($login->isLogin()) {
    $permission = new UsePermission($_COOKIE['user']);
    // var_dump($permission->getPermission());
    include_once ("./views/Navbar.php");
    //dashboard
    new Router('home', './views/Home.php', 'ລະບົບໂຮງຫວຍ');
    new Router('unit', './views/Unit.php', "ໜ່ວຍແຕ່ລະແຂວງ Dashboard");
    //==================== Management ========================
    //unit
    $unit = $permission->getValue("dataUnit");
    $permission->setView(1, $unit, './views/DataUnit.php', "ຂໍ້ມູນໜ່ວຍ");
    new Router("formUnit", "./views/form/unit_form.php", "ເພີ່ມຂໍ້ມູນໜ່ວຍ");
    new Router("editunit", "./views/form/edit_unit_form.php", "ແກ້ໄຂຂໍ້ມູນໜ່ວຍ");
    //lotmachine
    $lotmachine = $permission->getValue("lotmachine");
    $permission->setView(1, $lotmachine, './views/MainLotMachine.php', "ຈັດການລະຫັດຜູ້ຂາຍ");
    //Province
    $province = $permission->getValue("province");
    $permission->setView(1, $province, './views/Province.php', "ຂໍ້ມູນແຂວງ");
    //user
    $user = $permission->getValue("user");
    $permission->setView(1, $user, "./views/User.php", "ຂໍ້ມູນຜູ້ໃຊ້ງານ");
    new Router("createuser", "./views/form/user_form.php", "ເພີ່ມຜູ້ໃຊ້ງານ");
    new Router("edituser", "./views/form/edit_user_form.php", "ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້ງານ");
    //Permissions
    $permiss = $permission->getValue("permission");
    $permission->setView(1, $permiss, "./views/Permission.php", "ສິດທິການໃຊ້ງານ");
    new Router("addpermission", "./views/AddPermission.php", "ກຳນົດສິດທິ");
    new Router("updatePermission", "./views/UpdatePermission.php", "ແກ້ໄຂສິດທິ");
    //====================== Lottery ============================
    //lots
    $lottery = $permission->getValue("lottery");
    $permission->setView(1, $lottery, "./views/Lottery.php", "ຂໍ້ມູນເລກທີ");
    //select lottery
    $selectLot = $permission->getValue("selectlot");
    $permission->setView(1, $selectLot, "./views/selectLottery.php", "ປ້ອນຂໍ້ມູນງວດທີ");
    // financial
    new Router("financial", "./views/Financial.php", "ປ້ອນຂໍ້ມູນ");
    new Router("inputFinancial", "./views/InputFinancial.php", "ປ້ອນຂໍ້ມູນ");
    //Payment
    $payment = $permission->getValue("payment");
    $permission->setView(1, $payment, "./views/payment.php", "ເລືອກໜ່ວຍຖອກເງິນ");
    new Router("listpayment", "./views/listPayment.php", "ລາຍການຖອກເງິນ");
    new Router("history", "./views/HistoryPayment.php", "ປະຫວັດການຖອກເງິນ");
    //Read PDF
    $salePDF = $permission->getValue("salepdf");
    $permission->setView(1, $salePDF, "./views/MainPDFSale.php", "ຂໍ້ມູນ PDF ຍອດຂາຍ ແລະ ຖືກລາງວັນ");
    new Router("readsalepdf", "./views/MainScanSalePDF.php", "ອ່ານ PDF ຍອດຂາຍ");
    new Router("scanpayment", "./views/MainScanPDF.php", "ອ່ານ PDF ຍອດຂາຍ ແລະ ຖືກລາງວັນ");
    //PDF Awards
    $awardPDF = $permission->getValue("awardpdf");
    $permission->setView(1, $salePDF, "./views/ScanPDFlottery.php", "ອ່ານ PDF ບິນຖືກລາງວັນ");
    //Print page
    new Router("printsalepdf", "./views/PrintSalePDF.php", "ປີ້ນ PDF ຍອດຂາຍ ແລະ ຖືກລາງວັນ");
    new Router("printpdflottery", "./views/PrintPDFLottery.php", "ອ່ານ PDF ຖືກລາງວັນ");
    //============================== Debt =======================
    // Debt
    $debt = $permission->getValue("debt");
    $permission->setView(1, $debt, "./views/Debt.php", "ໃບແຈ້ງໜີ້/Invoice");

    $financialhistory =$permission->getValue("financialhistory");
    $permission->setView(1, $financialhistory, "./views/FinancialHistory.php", "ລາຍການປ້ອນຂໍ້ມູນແຕ່ລະງວດ");
    //==================== Report ==============================
    //Reports
    $reportfinancial = $permission->getValue("reportfinancial");
    $permission->setView(1, $reportfinancial, "./views/report/reportFinancial.php", "ລາຍງານການຂາຍ");

    $reportDebt = $permission->getValue("debtreport");
    $permission->setView(1, $reportfinancial, "./views/report/Debt_report.php", "ລາຍງານໜີ້ຕ້ອງຮັບ-ຕ້ອງສົ່ງ");

    $reportpayment = $permission->getValue("reportpayment");
    $permission->setView(1, $reportpayment, "./views/report/reportPayment.php", "ລາຍງານການຖອກເງິນປະຈຳວັນ");

    $reportSalePDF = $permission->getValue("reportsalepdf");
    $permission->setView(1, $reportSalePDF, "./views/report/reportSalePDF.php", "ລາຍງານ PDF ການຂາຍ");

    $reportMachine = $reportSalePDF = $permission->getValue("reportmachine");
    $permission->setView(1, $reportMachine, "./views/report/reportMachine.php", "ລາຍງານເຄື່ອງທີ່ບໍ່ເປີດຂາຍ");
    //footer
    require_once ("./views/Footer.php");
} else {
    $login->view();
}
