<?php
include_once("./database/GetUpdateUnit.php");
?>
<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <form class="border rounded-2 bg-white p-4" id="frmUnit">
        <div class="mb-3">
            <label for="cbprovinces" class="form-label">ແຂວງ</label>
            <select class="form-select" id="cbprovinces" name="cbprovinces">
                <?php
                include_once("./database/Province_Options.php");
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">ຊື່ໜ່ວຍ</label>
            <input type="text" class="form-control" name="unitname">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">ຈຳນວນເຄດິດ</label>
            <input type="number" class="form-control" max="10" min="4" name="credit" value="4">
        </div>
        <div class="mb-3">
            <label for="" class="form-label">ຈຳນວນເປີເຊັນ</label>
            <input type="number" class="form-control" max="30" min="10" name="Percentage" value="20">
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="moneyState" name="moneyState">
                <label class="form-check-label" for="moneyState">
                    ຈ່າຍເງິນ
                </label>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary w-100" type="submit">ບັນທຶກຂໍ້ມູນ</button>
            <a class="btn btn-warning w-100" href="?page=dataUnit">ກັບຄືນ</a>
        </div>
    </form>
</div>
<script>
    const formUnit = $("#frmUnit");
    formUnit.on('submit', (e) => {
        e.preventDefault();
        create();
    });
    const create = () => {
        const fromData = formUnit.serialize();
        const res = $.post(`./api/unitAPI.php?api=create`, fromData, (res) => {
            console.log(res);
            if (res.state) {
                location.href = "?page=dataUnit";
            }
        });
    }
</script>