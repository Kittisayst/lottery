<div class="container content">
    <?php
    require_once("./views/Alert.php");
    $startVal = isset($_GET['startdate']) ? $_GET['startdate'] : "";
    $endVal = isset($_GET['enddate']) ? $_GET['enddate'] : "";

    ?>
    <script>
        $(function() {
            $("#datepicker").datepicker();
            $("#datepickerEnd").datepicker();
        });
    </script>

    <!-- ຟອມຄົ້ນຫາຂໍ້ມູນ -->
    <form id="frmSearch">
        <div class="mb-3 d-flex justify-content-end gap-2">
            <div class="d-flex align-items-center gap-2">
                <label for="datepicker" class="form-label">ວັນທີ</label>
                <input type="text" id="datepicker" name="startdate" class="form-control" value="<?= $startVal ?>" autocomplete="off">
                <label for="datepickerEnd" class="form-label">ຫາ</label>
                <input type="text" id="datepickerEnd" name="enddate" class="form-control" value="<?= $endVal ?>" autocomplete="off">
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbProvince" class="form-label">ແຂວງ</label>
                <select class="form-select" aria-label="Default select example" name="pid" id="cbProvince">
                    <?php
                    include_once("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                <select class="form-select" aria-label="Default select example" name="unitID" id="cbUnit">
                    <?php
                    include_once("./database/unit_Option.php");
                    ?>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class='bx bx-search'></i> ສະແດງ</button>
            </div>
        </div>
    </form>

    <!-- ຕາຕະລາງລາຍງານ -->
    <table class="table table-bordered" id="tableReport">
        <thead>
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ຫັກເບີເຊັນ</th>
                <th scope="col">ຍອດຂາຍ</th>
                <th scope="col">ລາງວັນ</th>
                <th scope="col">ເປີເຊັນ</th>
                <th scope="col">ຍອດເຫຼືອ</th>
            </tr>
        </thead>
        <tbody id="tabledata">
            <tr class="text-center">
                <!-- <td colspan="8">..... ຄົ້ນຫາລາຍງານລົງບັນຊີ .....</td> -->
                <?php
                include_once("./components/reportFinancialTable.php");
                ?>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <button class="btn btn-success" id="btnexport"><i class='bx bx-grid'></i> Export Excel</button>
    </div>

</div>
<script>
    const tableData = $("#tabledata");

    const isExport = () => {
        const table = document.getElementById("tableReport");
        var rowCount = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
        $("#btnexport").prop("disabled", rowCount <= 1);
    }

    isExport();

    //Export Excel
    $("#btnexport").on("click", () => {
        const Month = new Date().getMonth() + 1;
        const table = document.getElementById("tableReport");
        const workbook = XLSX.utils.table_to_book(table, {
            sheet: "ລາຍງານລົງບັນຊີ"
        });
        XLSX.writeFile(workbook, `ລົງບັນຊີບັນຈຳເດືອນ ${Month} ${jdateTimeNow()}.xlsx`);
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

    $("#cbProvince").on("change", (e) => {
        const provinceID = e.target.value;
        $.get(`./api/unitAPI.php?api=unitbyprovinid&pid=${provinceID}`, (res, err) => {
            $("#cbUnit").html("");
            const units = res.data;
            const optionall = $(`<option value="0">---ໜ່ວຍທັງໝົດ---</option>`);
            $("#cbUnit").append(optionall);
            units.forEach(unit => {
                const option = $(`<option value="${unit['unitID']}">${unit['unitName']}</option>`);
                $("#cbUnit").append(option);
            });
        });
    });

    $("#frmSearch").on("submit", (e) => {
        e.preventDefault();
        const fromData = $("#frmSearch").serializeArray();
        console.log(fromData);
        const startDate = fromData[0];
        const endDate = fromData[1];
        const province = fromData[2];
        const unit = fromData[3];
        const url = `?page=reportfinancial&${startDate.name}=${startDate.value}&${endDate.name}=${endDate.value}&${province.name}=${province.value}&${unit.name}=${unit.value}`;
        location.href = url;
    })
</script>