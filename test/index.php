<?php
session_start();
?>
<div class="container content">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header position-relative py-5">
                    <img src="./public/loterry.png" class="position-absolute top-0 start-50 translate-middle border rounded-circle border-5" alt="lottery logo" width="150px">
                    <h3 class="position-absolute bottom-0 start-50 translate-middle-x">ລະບົບບັນຊີໂຮງຫວຍ</h3>
                </div>
                <div class="card-body">
                    <form id="frmlogin" method="post" action="athu.php">
                        <div class="mb-3">
                            <label for="txtuser" class="form-label">ຊື່ຜູ້ໃຊ້ງານ</label>
                            <input type="text" class="form-control" name="txtuser" id="txtuser" placeholder="Enter User" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">ລະຫັດຜ່ານ</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                        </div>
                        <div id="ms" class="mb-3 text-danger">
                            <?php
                            if (isset($_SESSION['ms'])) {
                                echo $_SESSION['ms'];
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-lg btn-primary w-100">ເຂົ້າສູ່ລະບົບ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
var_dump($_SESSION['user']);
?>