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
                <th scope="col">ງວດທີ</th>
                <th scope="col">ວັນທີ</th>
                <th scope="col">ຊື່ໄຟລ໌</th>
                <th scope="col">ຂະໜາດ</th>
                <th scope="col" style="width: 170px;">ຈັດການ</th>
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
</script>