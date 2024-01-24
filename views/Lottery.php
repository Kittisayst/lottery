<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <div class="mb-3">
        <button class="btn btn-primary " id="btnadd" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class='bx bx-plus-medical'></i> ເພີ່ມງວດທີ</button>
    </div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ງວດທີ</th>
                <th scope="col">ປະຈຳວັນທີ</th>
                <th scope="col">ເລກອອກ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">

        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-3 text-center w-100" id="exampleModalLabel">ເພີ່ມງວດທີ</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="px-3" id="frmadd">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lotno" class="form-label">ງວດທີ</label>
                        <input type="number" name="lotteryNo" id="lotno" class="form-control" placeholder="ງວດທີ" min="0" value="<?php include("./database/lotteryMax.php"); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lotdate" class="form-label">ວັນທີ</label>
                        <input type="date" name="lotDate" id="lotdate" class="form-control" placeholder="ວັນທີ" value="<?= date("Y-m-d") ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lotteryCorrect" class="form-label">ເລກອອກ</label>
                        <input type="number" name="lotteryCorrect" id="lotteryCorrect" class="form-control" placeholder="ເລກອອກ" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-center gap-2 w-100">
                        <button type="submit" class="btn btn-primary w-100">ເພີ່ມງວດ</button>
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">ປິດ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="./util/Myformat.js"></script>
<script>
    const show = () => {
        $.get(`./api/LotteryAPI.php?api=getlotterys`, (res) => {
            const lotterys = res.data;
            lotterys.forEach((lottery, index) => {
                const tr = $("<tr></tr>");
                tr.html(`
                    <th scope="row" class="text-center">${index+1}</th>
                    <td class="text-center">${lottery['lotteryNo']}</td>
                    <td class="text-center">${formatDate(lottery['lotDate'])}</td>
                    <td class="text-center">${lottery['lotteryCorrect']}</td>
                `);
                tr.append(createAction(lottery));
                $('#tbdata').append(tr);
            });
        });
    }

    show();

    const createAction = (lottery) => {
        const action = $("<td class='col-2 text-center'></td>");
        const button = $("<button class='btn btn-success btn-sm'><i class='bx bxs-edit'></i></button>");
        button.click(() => {
            Swal.fire({
                html: `
                <form id="fromUpdate" class="px-2 mt-5">
                    <div class="mb-3">
                        <input type="text" name="lotteryID" value="${lottery['lotteryID']}" hidden>
                        <label for="lotno" class="form-label w-100 text-start">ງວດທີ</label>
                        <input type="number" name="lotteryNo" id="elotno" class="form-control" placeholder="ງວດທີ" min="0" value="${lottery['lotteryNo']}" required>
                    </div>
                    <div class="mb-3">
                        <label for="lotdate" class="form-label w-100 text-start">ວັນທີ</label>
                        <input type="date" name="lotDate" id="lotdate" class="form-control" placeholder="ວັນທີ" value="${lottery['lotDate']}" required>
                    </div>
                    <div class="mb-5">
                        <label for="lotteryCorrect" class="form-label w-100 text-start">ເລກອອກ</label>
                        <input type="number" name="lotteryCorrect" id="lotteryCorrect" class="form-control" placeholder="ເລກອອກ" value="${lottery['lotteryCorrect']}" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success w-100"><i class='bx bxs-edit'></i> ແກ້ໄຂ</button>
                    </div>
                </form>`,
                showConfirmButton: false,
                showCloseButton: true,
                focusCancel: false
            });

            $("#elotno").focus();
            const fromUpdate = $("#fromUpdate");
            fromUpdate.on("submit", (e) => {
                e.preventDefault();
                const formData = fromUpdate.serialize();
                $.post(`./api/LotteryAPI.php?api=update`, formData, (res) => {
                    console.log(res);
                    if (res.state) {
                        Swal.fire({
                            position: "center",
                            icon: res.data,
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).finally(() => {
                            location.reload();
                        });
                    }
                });
            });
        });
        action.append(button);
        return action;
    }


    const fromlot = $("#frmadd");
    fromlot.on('submit', (e) => {
        e.preventDefault();
        const formData = fromlot.serialize();
        $.post(`./api/LotteryAPI.php?api=create`, formData, (result) => {
            console.log(result);
            if (result.state) {
                Swal.fire({
                    position: "center",
                    icon: result.data,
                    title: result.message,
                    showConfirmButton: false,
                    timer: 1500
                }).finally(() => location.href = "?page=lottery");
            }
        });
    });
</script>