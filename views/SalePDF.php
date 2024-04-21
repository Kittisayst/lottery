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
                <th scope="col" style="width: 150px;">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            require_once ("./database/SalePDFTable.php");
            ?>
        </tbody>
    </table>
</div>