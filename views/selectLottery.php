<?php
if (isset($_GET['datelot'])) {
    $value = $_GET['datelot'];
} else {
    $value = date("m-Y");
    echo "<script>
    location.href = '?page=selectlot&datelot=$value';
    </script>";
}

?>
<script>
    $(function() {
        $("#lotsearchdate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm-yy',
        });
    });
</script>
<div class="container content">
    <h1 class="p-2 text-center">ເລືອກງວດທີ່ຕ້ອງການປ້ອນ</h1>
    <div class="mb-3">
        <form class="d-flex justify-content-center align-items-center gap-2" id="frmshowlot">
            <div>
                <label for="lotsearchdate" class="form-label">ເລືອກເດືອນ: </label>
            </div>
            <div>
                <input type="text" id="lotsearchdate" name="datelot" value="<?= $value ?>" class="form-control" autocomplete="off">
            </div>
            <div>
                <button class="btn btn-success" type="submit">ສະແດງ</button>
            </div>

        </form>

    </div>
    <div class="d-flex justify-content-center flex-wrap gap-2">
        <?php
        include_once("./database/lotteryView.php");
        ?>
    </div>
    <script>
        const frmshowlot = $("#frmshowlot");
        frmshowlot.on("submit", (e) => {
            e.preventDefault();
            const data = frmshowlot.serializeArray();
            location.href = `?page=selectlot&${data[0].name}=${data[0].value}`;
        });
    </script>
</div>