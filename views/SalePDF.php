<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="mb-3">
        <div>
            <a class="btn btn-primary" href="?page=readsalepdf"><i class="bi bi-file-pdf-fill"></i> ອ່ານ PDF ຍອດຂາຍ</a>
        </div>
    </div>
    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ເລກທີໃນລະບົບ</th>
                <th scope="col">ງວດທີ</th>
                <th scope="col">ວັນທີ</th>
                <th scope="col">ຊື່ໄຟລ໌</th>
                <th scope="col">ຂະໜາດ</th>
                <th scope="col" style="width: 245px;">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            require_once ("./database/SalePDFTable.php");
            ?>
        </tbody>
    </table>
</div>
<script>
    $(function () {
        $("#datepicker").datepicker({
            dateFormat: "dd/mm/yy"
        });
    });
</script>
<!-- Modal -->
<div class="modal fade" id="modalEditPDF" tabindex="-1" aria-labelledby="modalEditPDFLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="modalEditPDFLabel">ແກ້ໄຂຂໍ້ມູນ PDF ການຂາຍ</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="frmEdit">
                <div class="mb-3">
                    <label for="txtlotno" class="form-label">ເລກທີ</label>
                    <input type="text" class="form-control" name="lotteryNo" id="txtlotno">
                </div>
                <div class="mb-3">
                    <label for="cblotid" class="form-label">ງວດທີໃນລະບົບ</label>
                    <select class="form-select" name="lotteryID" id="cblotid">
                        <?php
                        require_once "./database/LotteryOption.php";
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="datepicker" class="form-label">ວັນທີ</label>
                    <input type="text" class="form-control" name="lotDate" id="datepicker">
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-floppy-fill"></i>
                        ແກ້ໄຂຂໍ້ມູນ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const deletePDF = (id) => {
        Swal.fire({
            title: 'ລົບຂໍ້ມູນ PDF ຍອດຂາຍ?',
            text: "ທ່ານຕ້ອງການລົບຂໍ້ມູນນີ້ຫຼືບໍ່!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ລົບຂໍ້ມູນ',
            cancelButtonText: 'ຍົກເລີກ',
        }).then((result) => {
            if (result.isConfirmed) {
                $.get(`./api/SalePDFAPI.php?api=delete&id=${id}`, (res) => {
                    if (res.state) {
                        Swal.fire({
                            position: "center",
                            icon: res.data,
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).finally(() => location.reload());
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: res.data,
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    function handelEdit(id) {
        $("#modalEditPDF").modal("show");
        $.get(`./api/SalePDFAPI.php?api=getbyid&id=${id}`, (res) => {
            const pdfData = res.data;
            $("#txtlotno").val(pdfData.lotteryNo);
            $("#cblotid").val(pdfData.lotteryID);
            $("#datepicker").val(fomatdate(pdfData.lotDate));
        });

        $("#frmEdit").on("submit", (e) => {
            e.preventDefault();
            const frmdata = $("#frmEdit").serialize();
            $.post(`./api/SalePDFAPI.php?api=update&id=${id}`, frmdata, (res) => {
                if (res.state) {
                    Swal.fire({
                        text: res.message,
                        icon: res.data
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        text: res.message,
                        icon: res.data
                    });
                }
            })
        })
    }

    const fomatdate = (strdate = "") => {
        const condate = strdate.split("-");
        return `${condate[2]}/${condate[1]}/${condate[0]}`;
    }

    $("#alert_title").text($("#alert_title").text()+" (ລອດລີ້ງ)");
</script>