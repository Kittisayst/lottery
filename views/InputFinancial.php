<div class="container content">
    <div class="d-flex justify-content-center mt-3">
        <form class="border rounded p-5 d-flex flex-column col-6">
            <div class="mb-4">
                <h3 class="text-center" id="titleinput">Title</h3>
            </div>
            <div class="d-flex gap-2 mb-3">
                <div class="w-50">
                    <label for="" class="form-label">ງວດທີ</label>
                    <input type="text" class="form-control" placeholder="ງວດທີ" value="123">
                </div>
                <div class="w-50">
                    <label for="" class="form-label">ວັນທີ</label>
                    <input type="date" class="form-control" value="<?php echo date("Y-m-d") ?>" placeholder="ງວດທີ" value="123">
                </div>
            </div>
            <div class="mb-3">
                <label for="" class="form-label" name="">ຍອດຂາຍ</label>
                <input type="text" class="form-control" placeholder="ປ້ອນຍອດຂາຍ">
            </div>
            <div class="mb-3 d-flex gap-2">
                <div class="w-50">
                    <label for="" class="form-label">ຫັກເບີເຊັນ</label>
                    <input type="number" class="form-control" placeholder="ປ້ອນຫັກເບີເຊັນ">
                </div>
                <div class="w-50">
                    <label for="" class="form-label">ຍອດເຫຼືອ</label>
                    <input type="text" class="form-control" placeholder="ປ້ອນຍອດເຫຼືອ">
                </div>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">ລາງວັນ</label>
                <input type="text" class="form-control" placeholder="ປ້ອນລາງວັນ">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">ລາຍຈ່າຍ</label>
                <input type="text" class="form-control" placeholder="ປ້ອນລາຍຈ່າຍ">
            </div>
            <div class="mb-3 d-flex gap-2">
                <div class="w-50">
                    <label for="" class="form-label">ເງິນສົດ</label>
                    <input type="text" class="form-control" placeholder="ປ້ອນເງິນສົດ">
                </div>
                <div class="w-50">
                    <label for="" class="form-label">ເງິນສົດໂອນ</label>
                    <input type="text" class="form-control" placeholder="ປ້ອນເງິນສົດໂອນ">
                </div>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">ຍອດຄ້າງ</label>
                <input type="text" class="form-control" placeholder="ປ້ອນຍອດຄ້າງ">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">ໝາຍເຫດ</label>
                <textarea name="" class="form-control" id="" cols="15" rows="5" placeholder="ໝາຍເຫດ"></textarea>
            </div>
            <div class="mb-3 d-flex gap-3">
                <button class="btn btn-success w-50"><i class='bx bxs-save'></i> ບັນທຶກ</button>
                <a class="btn btn-warning w-50" href="?page=financial"><i class='bx bx-arrow-back'></i> ກັບຄືນ</a>
            </div>
        </form>
    </div>
</div>
<script>
    // Get the query string from the current URL
    const queryString = window.location.search;
    // Create a URLSearchParams object using the query string
    const urlParams = new URLSearchParams(queryString);
    const unitID = urlParams.get('unitID');
    $.get(`./api/unitAPI.php?api=getunitbyID&unitID=${unitID}`, (res) => {
        const unit = res.data[0];
        $("#titleinput").text("ໜ່ວຍ: "+unit['unitName']);
    })
</script>