<?php
include_once ("./database/Dashboard.php");
require_once "./database/StartPermission.php";
$permission = new UsePermission($_COOKIE['user']);
//ຈັດການຂໍ້ມູນ
$unitMenu = $permission->isMenu("dataUnit");
$lotmachineMenu = $permission->isMenu("lotmachine");
$provinceMenu = $permission->isMenu("province");
$userMenu = $permission->isMenu("user");
$permissionMenu = $permission->isMenu("permission");
$showManageMenu = array_sum([$unitMenu, $lotmachineMenu, $provinceMenu, $userMenu, $permissionMenu]) > 0;
//ປ້ອນຂໍ້ມູນ
$lotteryMenu = $permission->isMenu("lottery");
$selectlotMenu = $permission->isMenu("selectlot");
$paymentMenu = $permission->isMenu("payment");
$salepdfMenu = $permission->isMenu("salepdf");
$awardpdfMenu = $permission->isMenu("awardpdf");
$showInputData = array_sum([$lotteryMenu, $selectlotMenu, $paymentMenu, $salepdfMenu, $awardpdfMenu]) > 0;
//ທວງໜີ້
$debtMenu = $permission->isMenu("debt");
//ລາຍງານ
$reportfinancialMenu = $permission->isMenu("reportfinancial");
$debtreportMenu = $permission->isMenu("debtreport");
$reportpaymentMenu = $permission->isMenu("reportpayment");
$reportmachineMenu = $permission->isMenu("reportmachine");
$showReport = array_sum([$reportfinancialMenu, $debtreportMenu, $reportpaymentMenu, $reportmachineMenu]) > 0;
?>
<nav class="navbar navbar-expand-lg shadow-sm fixed-top" style="background-color: #ccc2a4;" id="navid">
    <div class="container">
        <a class="navbar-brand" href="?page=home"><img src="./public/imglogo.png" alt="nav logo" width="40px"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?page=home"><i class='bx bxs-home'></i>
                        ໜ້າຫຼັກ</a>
                </li>
                <?php if ($showManageMenu): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class='bx bxs-data'></i> ຈັດການຂໍ້ມູນ
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            //=====================ຈັດການຂໍ້ມູນໜ່ວຍ=====================
                            $showUnit = $unitMenu ? ' <li>
                                <a class="dropdown-item" href="?page=dataUnit">
                                    <i class="bx bxs-user-pin"></i>
                                    ຈັດການຂໍ້ມູນໜ່ວຍ
                                </a>
                            </li>' : "";
                            echo $showUnit;
                            //=====================ຈັດການລະຫັດຜູ້ຂາຍ======================
                            $showLotMachince = $lotmachineMenu ? '<li>
                                <a class="dropdown-item" href="?page=lotmachine" id="lotselect">
                                    <i class="bx bxs-book-reader"></i>
                                    ຈັດການລະຫັດຜູ້ຂາຍ
                                </a>
                            </li>' : "";
                            echo $showLotMachince;
                            //======================ຈັດການຂໍ້ມູນແຂວງ=======================
                            $showProvince = $provinceMenu ? '<li>
                                <a class="dropdown-item" href="?page=province">
                                    <i class="bx bxs-buildings"></i>
                                    ຈັດການຂໍ້ມູນແຂວງ
                                </a>
                            </li>' : "";
                            echo $showProvince;
                            //======================ຈັດການຂໍ້ມູນຜູ້ໃຊ້ງານ========================
                            $showUser = $userMenu ? '<li>
                                <a class="dropdown-item" href="?page=user">
                                    <i class="bx bxs-user-circle"></i>
                                    ຈັດການຂໍ້ມູນຜູ້ໃຊ້ງານ
                                </a>
                            </li>' : "";
                            echo $showUser;
                            //=======================ສິດທິການໃຊ້ງານ=====================
                            $showPermission = $permissionMenu ? '<li>
                                <a class="dropdown-item" href="?page=permission">
                                    <i class="bi bi-shield-lock-fill"></i>
                                    ສິດທິການໃຊ້ງານ
                                </a>
                            </li>' : "";
                            echo $showPermission;
                            ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($showInputData): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class='bx bxs-book-bookmark'></i> ປ້ອນຂໍ້ມູນ
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            //=========================ງວດທີເລກ===========================
                            $showlottery = $lotteryMenu ? '<li>
                                <a class="dropdown-item" href="?page=lottery">
                                    <i class="bx bxs-notepad"></i>
                                    ງວດທີເລກ
                                </a>
                            </li>' : "";
                            echo $showlottery;
                            //===========================ປ້ອນຂໍ້ມູນ==========================
                            $showselectlot = $selectlotMenu ? '<li>
                                <a class="dropdown-item" href="?page=selectlot">
                                    <i class="bx bx-money"></i>
                                    ປ້ອນຂໍ້ມູນ
                                </a>
                            </li>' : "";
                            echo $showselectlot;
                            //=============================ຖອກເງິນ======================
                            $showpayment = $paymentMenu ? '<li>
                                <a class="dropdown-item" href="?page=payment">
                                    <i class="bx bxs-user-badge"></i>
                                    ຖອກເງິນ
                                </a>
                            </li>' : "";
                            echo $showpayment;
                            //============================ຂໍ້ມູນ PDF ຍອດຂາຍ=========================
                            $showsalepdf = $salepdfMenu ? '<li>
                                <a class="dropdown-item" href="?page=salepdf">
                                    <i class="bx bxs-file"></i>
                                    ສະແກນໄຟລ໌-ຍອດຂາຍ
                                </a>
                            </li>' : "";
                            echo $showsalepdf;
                            //=============================ອ່ານ PDF ບິນຖືກລາງວັນ======================
                            $showawardpdf = $awardpdfMenu ? '<li>
                                <a class="dropdown-item" href="?page=awardpdf">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                    ສະແກນໄຟລ໌-ຖືກລາງວັນ
                                </a>
                            </li>' : "";
                            echo $showawardpdf;
                            ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($debtMenu): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=debt"><i class='bx bxs-bank'></i> ທວງໜີ້</a>
                    </li>
                <?php endif; ?>

                <?php if ($showReport): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class='bx bxs-report'></i>
                            ລາຍງານ
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            //===========================ລາຍງານການຂາຍ=================================
                            $showreportfinancial = $reportfinancialMenu ? '<li>
                                <a class="dropdown-item" href="?page=reportfinancial">
                                    <i class="bx bxs-calendar"></i>
                                    ລາຍງານການຂາຍ
                                </a>
                            </li>' : "";
                            echo $showreportfinancial;
                            //===========================ລາຍງານໜີ້ຕ້ອງຮັບ-ຕ້ອງສົ່ງ==========================
                            $showdebtreport = $debtreportMenu ? '<li>
                                <a class="dropdown-item" href="?page=debtreport">
                                    <i class="bx bxs-group"></i>
                                    ລາຍງານໜີ້ຕ້ອງຮັບ-ຕ້ອງສົ່ງ
                                </a>
                            </li>' : "";
                            echo $showdebtreport;
                            //===========================ລາຍງານການຖອກເງິນປະຈຳວັນ==========================
                            $showreportpayment = $reportpaymentMenu ? '<li>
                                <a class="dropdown-item" href="?page=reportpayment">
                                    <i class="bx bx-money-withdraw"></i>
                                    ລາຍງານການຖອກເງິນປະຈຳວັນ
                                </a>
                            </li>' : "";
                            echo $showreportpayment;
                            //============================ລາຍງານເຄື່ອງທີ່ບໍ່ເປີດຂາຍ=============================
                            $showreportmachine = $reportmachineMenu ? '<li>
                                <a class="dropdown-item" href="?page=reportmachine">
                                    <i class="bi bi-send-x-fill"></i>
                                    ລາຍງານເຄື່ອງທີ່ບໍ່ເປີດຂາຍ
                                </a>
                            </li>' : "";
                            echo $showreportmachine;
                            ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="me-5">
                <div class=" position-relative" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class='bx bxs-bell fs-3 text-white btn btn-sm'></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= getDebtCount() ?>
                    </span>
                </div>
            </div>
            <div class="d-flex" role="search">
                <button class="btn btn-outline-danger border-light text-white" id="btnlogout"
                    type="submit">ອອກຈາກລະບົບ</button>
            </div>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <div class="list-group">
                    <?php getDebtModalDialog() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#btnlogout").click(() => {
        $.get("./api/userAPI.php?api=getlogout", (res, mes) => {
            console.log(res);
            if (res.state) {
                location.reload();
            }
        });
    });
</script>