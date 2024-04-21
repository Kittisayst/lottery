<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="d-flex justify-content-between w-100 bg-light p-3">
        <form class="d-flex align-items-center gap-2 me-auto" id="frmshowUint">
            <div class="d-flex align-items-center gap-2">
                <label for="cbProvince" class="form-label">ແຂວງ</label>
                <select class="form-select" name="provinceID" id="cbProvince">
                    <?php
                    include_once ("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                <select class="form-select" name="unitid" id="cbUnit">
                    <?php
                    include_once ("./database/unit_Option.php");
                    ?>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2 col-1s">
                <label for="cblotteryID" class="form-label w-50">ງວດທີ</label>
                <select class="form-select" name="lotteryID" id="cblotteryID">
                    <?php
                    require_once "./database/LotteryOption.php";
                    ?>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" id="btnshow">
                    <i class="bi bi-search"></i> ສະແດງ
                </button>
            </div>
        </form>
        <div>
            <a class="btn btn-success" href="?page=salepdf">
                <i class="bi bi-plus-circle-fill"></i> ອ່ານ PDF ການຂາຍ
            </a>
        </div>
    </div>
    <table class="table table-bordered table-striped mt-2" id="tbsales">
        <thead>
            <tr class="text-center align-middle">
                <th scope="col">ລຳດັບ</th>
                <th scope="col">ຫົວຂໍ້</th>
                <th scope="col">ໝາຍເຫດ</th>
                <th scope="col" style="width: 180px;">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tableData">
            <?php
            require_once "./database/TableReportSalePDF.php";
            ?>
        </tbody>
    </table>
</div>

<script>
    $("#frmshowUint").submit((e) => {
        e.preventDefault();
        const frm = $("#frmshowUint").serializeArray();
        location.href = `?page=reportsalepdf&pid=${frm[0].value}&unitID=${frm[1].value}&lotid=${frm[2].value}`;
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
</script>