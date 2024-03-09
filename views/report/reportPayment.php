<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <script>
        $(function() {
            $("#datepicker").datepicker();
            $("#datepickerEnd").datepicker();
        });
    </script>
    <form id="frmSearch">
        <div class="mb-3 d-flex justify-content-end gap-2">
            <div class="d-flex align-items-center gap-2">
                <label for="datepicker" class="form-label">ວັນທີ</label>
                <input type="text" id="datepicker" name="startdate" class="form-control" value="<?= isset($_GET['startdate']) ? $_GET['startdate'] : "" ?>" autocomplete="off">
                <label for="datepickerEnd" class="form-label">ຫາ</label>
                <input type="text" id="datepickerEnd" name="enddate" class="form-control" value="<?= isset($_GET['enddate']) ? $_GET['enddate'] : "" ?>" autocomplete="off">
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbProvince" class="form-label">ແຂວງ</label>
                <select class="form-select" name="provinceID" id="cbProvince">
                    <?php
                    include_once("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                <select class="form-select" name="unitid" id="cbUnit">
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
    <table class="table table-bordered table-hover" id="tableReport">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ເລກທີ</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ເງິນສົດ</th>
                <th scope="col">ເງິນໂອນ</th>
                <th scope="col">ບໍລິສັດຕິດລົບ</th>
                <th scope="col">ອື່ນໆ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            include_once("./database/reportPaymentTable.php");
            ?>
        </tbody>
    </table>
    <div class="fs-5 fw-bold d-flex justify-content-end align-items-center">
        <button class="btn btn-success" id="btnexport"><i class='bx bx-grid'></i> Export Excel</button>
    </div>
</div>
<script>
    const formsearch = $('#frmSearch');
    const tbdata = $('#tbdata');

    const isExport = () => {
        const table = document.getElementById("tableReport");
        var rowCount = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
        $("#btnexport").prop("disabled", rowCount <= 1);
    }

    formsearch.on("submit", (e) => {
        e.preventDefault();
        const formData = formsearch.serializeArray();
        console.log(formData);
        location.href = `?page=reportpayment&startdate=${formData[0].value}&enddate=${formData[1].value}&pid=${formData[2].value}&unitID=${formData[3].value}`;
    });

    isExport();

    //Export Excel
    $("#btnexport").on("click", () => {
        const Month = new Date().getMonth() + 1;
        const table = document.getElementById("tableReport");
        const workbook = XLSX.utils.table_to_book(table, {
            sheet: "ລາຍງານການຖອກເງິນ"
        });
        XLSX.writeFile(workbook, `ລາຍງານການຖອກເງິນ ${Month} ${jdateTimeNow()}.xlsx`);
    });

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