<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <form id="formsearch" method="get">
        <div class="d-flex justify-content-end gap-3 mb-3">
            <div class="">
                <select class="form-select" aria-label="Default select example" name="provinceID" id="cbprovince" required>
                    <?php
                    include("./database/Province_Options.php");
                    $search = isset($_GET['search']) ? $_GET['search'] : "";
                    ?>
                </select>
            </div>
            <div>
                <input type="search" id="txtsearch" name="search" class="form-control" value="<?=$search?>" placeholder="ຊື່ໜ່ວຍ/ໄອດີໜ່ວຍ">
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit" name="search" id="btnsearch"><i class='bx bxs-file-find'></i> ສະແດງ</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ຈຳນວນງວດຄ້າງ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            include_once("./database/UnitPaymentTable.php");
            ?>
        </tbody>
    </table>
</div>
<script>
    const formsearch = $('#formsearch');
    const tbdata = $('#tbdata');

    formsearch.on("submit", (e) => {
        e.preventDefault();
        const formData = formsearch.serializeArray();
        console.log(formData);
        location.href = `?page=payment&pid=${formData[0].value}&search=${formData[1].value}`;
        console.log(formData);
    });
</script>