<div class="container content">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-12 col-md-5 col-lg-4 shadow">
            <div class="card">
                <div class="card-header position-relative py-5">
                    <img src="./public/imglogo.png"
                        class="position-absolute top-0 start-50 translate-middle border rounded-circle border-5"
                        alt="lottery logo" width="130px">
                    <h3 class="position-absolute bottom-0 start-50 translate-middle-x fw-bold">ລະບົບບັນຊີໂຮງຫວຍ</h3>
                </div>
                <div class="card-body">
                    <form id="frmlogin">
                        <div class="d-flex justify-content-center gap-5 mb-3">
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" value="0" id="laolot" name="selectlot"
                                    checked>
                                <label class="form-check-label" for="laolot">
                                    <img src="./public/laolot.webp" alt="laolot" width="50px">
                                    <span class="fs-5">ລາວລອດ</span>
                                </label>
                            </div>
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" value="1" id="lotlink" name="selectlot">
                                <label class="form-check-label" for="lotlink">
                                    <img src="./public/lotlink.png" alt="lotlink" width="50px">
                                    <span class="fs-5">ລອດລີ້ງ</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="txtuser" class="form-label">ຊື່ຜູ້ໃຊ້ງານ</label>
                            <input type="text" class="form-control" name="txtuser" id="txtuser" placeholder="Enter User"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">ລະຫັດຜ່ານ</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter Password" required>
                        </div>
                        <div id="ms" class="mb-3 text-danger">
                        </div>
                        <div class="mb-3 mt-4">
                            <button type="submit" class="btn btn-primary w-100" id="btnlogin"><i
                                    class="bi bi-box-arrow-in-right"></i>
                                ເຂົ້າສູ່ລະບົບ(ລາວລອດ)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#frmlogin').on('submit', (e) => {
        e.preventDefault();
        $fromData = $('#frmlogin').serialize();
        console.log($fromData);
        $.post(`./api/userAPI.php?api=getlogin`, $fromData, (res, mes) => {
            if (res.state) {
                const lot = $('#frmlogin').serializeArray();
                localStorage.setItem("lot", lot[0].value);
                location.reload();
            } else {
                $("#ms").html(`<span class="text-danger">${res.message}</span>`);
            }
        });
    });

    $("#laolot").change(() => {
        $("#btnlogin").html(`<i class="bi bi-box-arrow-in-right"></i> ຂົ້າສູ່ລະບົບ (ລາວລອດ)`);
    });
    $("#lotlink").change(() => {
        $("#btnlogin").html(`<i class="bi bi-box-arrow-in-right"></i> ເຂົ້າສູ່ລະບົບ (ລອດລີ້ງ)`);
    });
</script>