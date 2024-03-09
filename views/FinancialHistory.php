<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <div class="mb-3 d-flex justify-content-between">
        <div class="me-auto">
            <button class="btn btn-secondary" id="btnback"><i class='bx bx-arrow-back'></i> ກັບຄືນ</button>
        </div>
        <div>
            <input type="search" class="form-control" name="search" id="txtSearch" placeholder="ຄົ້ນຫາ">
        </div>
    </div>
    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ງວດທີ</th>
                <th scope="col">ວັນທີ</th>
                <th scope="col">ຍອດຂາຍ</th>
                <th scope="col">ເປີເຊັນ</th>
                <th scope="col">ເປັນເງິນ</th>
                <th scope="col">ລາງວັນ</th>
                <th scope="col">ຍອດເຫຼືອ</th>
                <th scope="col">ເອກະສານ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            include_once("./database/financialHistoryTable.php");
            ?>
        </tbody>
    </table>
</div>

<script>
    $("#txtSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tbdata tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $("#btnback").click(() => {
        history.back();
    })
</script>