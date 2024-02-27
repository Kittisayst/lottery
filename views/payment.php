<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <form id="formsearch">
        <div class="d-flex justify-content-end gap-3 mb-3">
            <div class="">
                <select class="form-select" aria-label="Default select example" name="provinceID" id="cbprovince" required>
                    <?php
                    include("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div>
                <input type="search" id="txtsearch" class="form-control" placeholder="ຊື່ໜ່ວຍ/ໄອດີໜ່ວຍ">
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit" name="search"><i class='bx bxs-file-find'></i> ສະແດງ</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ງວດທີ່ຄ້າງ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <tr>
                <td colspan="4" class="text-center">---- ຄົ້ນຫາຂໍ້ມູນໜ່ວຍເພື່ອຖອກເງິນ ----</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    const formsearch = $('#formsearch');
    const tbdata = $('#tbdata');
    formsearch.on("submit", (e) => {
        e.preventDefault();
        const formData = formsearch.serialize();
        console.log(formData);
    });

    $("#cbprovince").on("change", (e) => {
        const provinceID = $(e.target).val();
        $.get(`./api/unitAPI.php?api=unitbyprovinid&pid=${provinceID}`, (res) => {
            tbdata.html("");
            const units = res.data;
            units.forEach((unit, index) => {
                const tr = $("<tr class='text-center'></tr>");
                tr.html(`
                <td>${index+1}</td>
                <td>${unit.unitName}</td>
                <td>${0}</td>
                `);
                const rowAction = $(`<td class="col-2"></td>`);
                const action = $(`<div class="d-flex flex-wrap gap-2 justify-content-center"></div>`);
                const buttonHistory = $(`<button class="btn btn-info btn-sm"><i class='bx bx-history' ></i> ປະຫວັດ</button>`);
                const buttonWithdraw = $(`<a href="?page=listpayment&unitid=${unit.unitID}" class="btn btn-warning btn-sm"><i class='bx bxs-dollar-circle'></i> ຖອກເງິນ</a>`);
                action.append(buttonHistory);
                action.append(buttonWithdraw);
                rowAction.append(action);
                tr.append(rowAction);
                tbdata.append(tr);
                //Button Event Handler
                buttonHistory.click(() => {  //Button Event Handler
                    alert("ok");
                });
            });
        });
    });
</script>