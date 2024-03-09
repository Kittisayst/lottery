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
                <th scope="col">ເລກທີ່ບິນ</th>
                <th scope="col">ວັນທີ</th>
                <th scope="col">ເງິນສົດ</th>
                <th scope="col">ເງິນໂອນ</th>
                <th scope="col">ບໍລິສັດຕິດລົບ</th>
                <th scope="col">ອື່ນໆ</th>
                <th scope="col">ປີ້ນບິນ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            include_once("./database/HistoryTable.php");
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