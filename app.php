<?php
require_once ("./router/routes.php");
require_once ("./controller/loginController.php"); // use start cookie
require_once ("./router/startRoute.php");

// Start a new route
$login = new LoginController();
if ($login->isLogin()) {
    include_once ("./views/Navbar.php");
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
    new Router("lotmachine", "./views/lotmachine.php", "ຈັດການລະຫັດຜູ້ຂາຍ");
    //user
    new Router("createuser", "./views/form/user_form.php", "ເພີ່ມຜູ້ໃຊ້ງານ");
    new Router("edituser", "./views/form/edit_user_form.php", "ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້ງານ");
    //select lottery
    new Router("selectlot", "./views/selectLottery.php", "ປ້ອນຂໍ້ມູນງວດທີ");
    //Payment
    new Router("payment", "./views/payment.php", "ເລືອກໜ່ວຍຖອກເງິນ");
    new Router("listpayment", "./views/listPayment.php", "ລາຍການຖອກເງິນ");
    new Router("history", "./views/HistoryPayment.php", "ປະຫວັດການຖອກເງິນ");
    //Read PDF
    new Router("salepdf","./views/SalePDF.php", "ຂໍ້ມູນ PDF ຍອດຂາຍ ແລະ ຖືກລາງວັນ");
    new Router("scanpdflottery","./views/ScanPDFlottery.php", "ອ່ານ PDF ບິນຖືກລາງວັນ");
    new Router("readsalepdf","./views/ScanPDFSale.php", "ອ່ານ PDF ຍອດຂາຍ");
    new Router("scanpayment","./views/ScanPDF.php", "ອ່ານ PDF ຍອດຂາຍ ແລະ ຖືກລາງວັນ");
    //Print page
    new Router("printsalepdf","./views/PrintSalePDF.php", "ປີ້ນ PDF ຍອດຂາຍ ແລະ ຖືກລາງວັນ");
    new Router("printpdflottery","./views/PrintPDFLottery.php", "ອ່ານ PDF ຖືກລາງວັນ");
    //Reports
    new Router("reportfinancial", "./views/report/reportFinancial.php", "ລາຍງານການຂາຍ");
    new Router("reportpayment", "./views/report/reportPayment.php", "ລາຍງານການຖອກເງິນປະຈຳວັນ");
    new Router("reportsalepdf", "./views/report/reportSalePDF.php", "ລາຍງານ PDF ການຂາຍ");
    new Router("reportmachine", "./views/report/reportMachine.php", "ລາຍງານເຄື່ອງທີ່ບໍ່ເປີດຂາຍ");
    // Debt
    new Router("debt", "./views/Debt.php", "ໃບແຈ້ງໜີ້/Invoice");
    new Router("debtreport", "./views/report/Debt_report.php", "ລາຍງານໜີ້ຕ້ອງຮັບ-ຕ້ອງສົ່ງ");
    new Router("financialhistory", "./views/FinancialHistory.php", "ລາຍການປ້ອນຂໍ້ມູນແຕ່ລະງວດ");
    //footer
    require_once ("./views/Footer.php");
} else {
    $login->view();
}
