<?php if (isset($_GET['id'])) : ?>
    <div class="container content">
        <?php require_once("./views/Alert.php") ?>
        <?php
        require_once("./database/getUnitbyID.php");
        $unitData = getUnitData();
        ?>
        <form class="border rounded-2 bg-white p-4" id="frmUnit">
            <div class="mb-3">
                <label for="cbprovinces" class="form-label">ແຂວງ</label>
                <select class="form-select" id="cbprovinces" name="cbprovinces">
                    <?php
                    getProvinceOption();
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">ຊື່ໜ່ວຍ</label>
                <input type="text" class="form-control" name="unitname" value="<?= $unitData['unitName'] ?>">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">ເປີເຊັນ</label>
                <input type="number" class="form-control" max="50" min="0" name="Percentage" value="<?= $unitData['Percentage'] ?>">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">ຈຳນວນເຄດິດ</label>
                <input type="number" class="form-control" max="10" min="4" name="credit" value="<?= $unitData['credit'] ?>">
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <?php
                    $monState = $unitData['withdrawn'] == 1 ? "checked" : "";
                    ?>
                    <input class="form-check-input" type="checkbox" name="moneyState" <?= $monState ?>>
                    <label class="form-check-label" for="moneyState">
                        ຈ່າຍເງິນ
                    </label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary w-100" type="submit">ແກ້ໄຂໜ່ວຍ</button>
                <a class="btn btn-warning w-100" href="?page=dataUnit">ກັບຄືນ</a>
            </div>
        </form>
    </div>
    <script>
        const frmUnit = $("#frmUnit");
        frmUnit.on("submit", (e) => {
            e.preventDefault();
            const formData = frmUnit.serialize();
            const res = $.post(`./api/unitAPI.php?api=update&id=<?= $unitData['unitID'] ?>`, formData, (result) => {
                console.log(result);
                if (result.state) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).finally(() => location.href = "?page=dataUnit");
                }
            });
        })
    </script>
<?php else : ?>
    <script>
        location.href = "?page=dataUnit";
    </script>
<?php endif ?>