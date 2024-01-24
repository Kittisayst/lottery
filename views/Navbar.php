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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bxs-data'></i> ຈັດການຂໍ້ມູນ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=dataUnit"><i class='bx bxs-user-pin'></i> ຈັດການຂໍ້ມູນໜ່ວຍ</a></li>
                        <li><a class="dropdown-item" href="?page=province"><i class='bx bxs-buildings'></i> ຈັດການຂໍ້ມູນແຂວງ</a></li>
                        <li><a class="dropdown-item" href="?page=user"><i class='bx bxs-user-circle'></i> ຈັດການຂໍ້ມູນຜູ້ໃຊ້ງານ</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bxs-book-bookmark'></i> ປ້ອນຂໍ້ມູນ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?page=lottery"><i class='bx bxs-notepad'></i> ງວດທີເລກ</a></li>
                        <li><a class="dropdown-item" href="?page=selectlot"><i class='bx bx-money'></i> ປ້ອນຂໍ້ມູນ</a></li>
                        <li><a class="dropdown-item" href="?page=payment"><i class='bx bxs-user-badge'></i> ຖອກເງິນ</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bxs-report'></i> ລາຍງານ
                    </a>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?page=reportfinancial"><i class='bx bxs-calendar'></i> ລາຍງານການປ້ອນຂໍ້ມູນ</a></li>
                        <li><a class="dropdown-item" href="#"><i class='bx bxs-calendar'></i> ລາຍງານປະຈຳວັນ</a></li>
                        <li><a class="dropdown-item" href="#"><i class='bx bxs-group'></i> ລາຍງານການຖອກເງິນ</a></li>
                        <li><a class="dropdown-item" href="#"><i class='bx bxs-calculator'></i> ລາຍງານຂໍ້ມູນບັນຊີທັງໝົດ</a></li>
                    </ul>
                </li>
            </ul>
            <div class="me-5">
                <div class=" position-relative">
                    <i class='bx bxs-bell fs-3 text-white btn btn-sm'></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        2
                    </span>
                </div>
            </div>
            <div class="d-flex" role="search">
                <button class="btn btn-outline-danger border-light text-white" id="btnlogout" type="submit">ອອກຈາກລະບົບ</button>
            </div>
        </div>
    </div>

</nav>

<script>
    $("#btnlogout").click(() => {
        $.get("./api/userAPI.php?api=getlogout", (res, mes) => {
            if (res.state) {
                location.reload();
            }
        });
    });
</script>