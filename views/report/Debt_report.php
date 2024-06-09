<script>
    $(function () {
        $("#txtdate").datepicker();
    });
</script>
<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <form id="frmsearch">
        <div class="d-flex justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2 me-auto">
                <label for="txtdate" class="form-label col-3">ເລືອກດືອນ</label>
                <input type="text" class="form-control" id="txtdate" name="lotdate" autocomplete="off" required>
            </div>
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
            <div>
                <button type="submit" class="btn btn-primary">ສະແດງ</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center align-middle">
                <th scope="col">ລ/ດ</th>
                <th scope="col">ຊື່ໜ່ວຍ</th>
                <th scope="col">ຍອດຍົກມາ</th>
                <th scope="col">ຍອດຂາຍ</th>
                <th scope="col">ເປີເຊັນ</th>
                <th scope="col">ຍອດຖອກ</th>
                <th scope="col">ລາງວັນ</th>
            </tr>
        </thead>
        <tbody id="tbdata">

        </tbody>
    </table>
</div>