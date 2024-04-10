<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
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
                <th scope="col">ເປີເຊັນ</th>
                <th scope="col">ຍອດຂາຍ</th>
                <th scope="col">ເປັນເງິນ</th>
                <th scope="col">ລາງວັນ</th>
                <th scope="col">ຍອດເຫຼືອ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            include_once ("./database/financialHistoryTable.php");
            ?>
        </tbody>
    </table>
    <div class="mb-3 text-end">
        <button class="btn btn-info col-3" id="btnPrint"><i class='bx bxs-printer'></i> ປີ້ນເອກະສານທວງໜີ້</button>
        <button class="btn btn-success col-3" id="btnexport"><i class='bx bxs-grid'></i> Export Excel</button>
    </div>
</div>

<script>
    $("#txtSearch").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#tbdata tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    $("#btnback").click(() => {
        history.back();
    })

    //Export Excel
    $("#btnexport").on("click", () => {
        const Month = new Date().getMonth() + 1;
        const table = document.getElementById("tbdata");
        const workbook = XLSX.utils.table_to_book(table, {
            sheet: "ການຖອກເງິນ"
        });
        XLSX.writeFile(workbook, `ລາຍງານການຖອກເງິນ ${Month} ${jdateTimeNow()}.xlsx`);
    });

    const jdateTimeNow = () => {
        const currentDate = new Date();
        const day = currentDate.getDate().toString().padStart(2, '0');
        const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
        const year = currentDate.getFullYear();
        const hours = currentDate.getHours().toString().padStart(2, '0');
        const minutes = currentDate.getMinutes().toString().padStart(2, '0');
        const seconds = currentDate.getSeconds().toString().padStart(2, '0');
        const formattedDateTime = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
        return formattedDateTime;
    }
</script>