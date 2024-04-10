<?php
include_once("./database/Dashboard.php");
$isaddmint = $_COOKIE['user'] == 1;
?>
<nav class="navbar navbar-expand-lg shadow-sm fixed-top" style="background-color: #ccc2a4;">
    <div class="container">
        <a class="navbar-brand" href="?page=home"><img src="./public/loterry.png" alt="nav logo" width="40px"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?page=home"><i class='bx bxs-home'></i> ໜ້າຫຼັກ</a>
                </li>
                <?php if ($isaddmint) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bxs-data'></i> ຈັດການຂໍ້ມູນ
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=dataUnit"><i class='bx bxs-user-pin'></i> ຈັດການຂໍ້ມູນໜ່ວຍ</a></li>
                            <li><a class="dropdown-item" href="?page=lotmachine"><i class='bx bxs-book-reader' ></i> ຈັດການລະຫັດຜູ້ຂາຍ</a></li>
                            <li><a class="dropdown-item" href="?page=province"><i class='bx bxs-buildings'></i> ຈັດການຂໍ້ມູນແຂວງ</a></li>
                            <li><a class="dropdown-item" href="?page=user"><i class='bx bxs-user-circle'></i> ຈັດການຂໍ້ມູນຜູ້ໃຊ້ງານ</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bxs-book-bookmark'></i> ປ້ອນຂໍ້ມູນ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=lottery"><i class='bx bxs-notepad'></i> ງວດທີເລກ</a></li>
                        <li><a class="dropdown-item" href="?page=selectlot"><i class='bx bx-money'></i> ປ້ອນຂໍ້ມູນ</a></li>
                        <li><a class="dropdown-item" href="?page=payment"><i class='bx bxs-user-badge'></i> ຖອກເງິນ</a></li>
                        <li><a class="dropdown-item" href="?page=scanpayment"><i class='bx bxs-file' ></i> ອ່ານ PDF ຍອດຂາຍ</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=debt"><i class='bx bxs-bank'></i> ທວງໜີ້</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bxs-report'></i> ລາຍງານ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=reportfinancial"><i class='bx bxs-calendar'></i> ລາຍງານປ້ອນຂໍ້ມູນທັງໝົດ</a></li>
                        <li><a class="dropdown-item" href="?page=reportfinancial"><i class='bx bxs-group'></i> ລາຍງານປ້ອນຂໍ້ມູນເປັນໜ່ວຍ</a></li>
                        <li><a class="dropdown-item" href="?page=reportpayment"><i class='bx bx-money-withdraw'></i> ລາຍງານການຖອກເງິນ</a></li>
                    </ul>
                </li>
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
                <button class="btn btn-outline-danger border-light text-white" id="btnlogout" type="submit">ອອກຈາກລະບົບ</button>
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