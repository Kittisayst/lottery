<link rel="stylesheet" href="./printer.css">
<style>
    @media print {
        #btnback {
            display: none;
        }
    }
</style>
<div class="d-flex flex-column print-p">
    <div class="mb-3">
        <div class="d-flex">
            <p class="fs-h  text-center col">ສັງລວມການຂາຍ</p>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-column">
            <span>ຕົວແທນແຂວງ: <spn id="pname" class="fw-bold"></spn></span>
            <span>ໜ່ວຍ: <spn id="uname" class="fw-bold"></spn></span>
            <span>ເບີໂທສາຂາ:...........................</span>
        </div>
        <div class="d-flex flex-column text-end">
            <span>
                ປະຈຳງວດ: <span id="lotno" class="fw-bold"></span>
                ຄັ້ງທີ່: <span id="lotdate" class="fw-bold"></span>
                ເລກທີ່ອອກ: <span id="lotcorrect" class="fw-bold"></span>
            </span>
            <span>ຍອດຂາຍ: <span id="sales" class="fw-bold"></span></span>
            <span>ຈຳນວນເຄື່ອງທີ່ເປີດຂາຍ: <span id="macOnline" class="fw-bold"></span></span>
            <span>ຈຳນວນເຄື່ອງທີ່ບໍ່ເປີດຂາຍ: <span id="macOffline" class="fw-bold"></span></span>
        </div>
    </div>
    <table class="table table-bordered table-striped mt-2" id="tbsales">
        <thead class="fs-thead">
            <tr class="text-center align-middle">
                <th scope="col">ລຳດັບ</th>
                <th scope="col">ລະຫັດຜູ້ຂາຍ</th>
                <th scope="col">ມູນຄ່າຂາຍໄດ້</th>
                <th scope="col">ມູນຄ່າຖືກລາງວັນ</th>
                <td scope="col" colspan="2">
                    <span class="fw-bold">ຜູ້ຂາຍໜ່ວຍ</span>
                    <div class="d-flex">
                        <span class="text-center col border-end">%</span>
                        <span class="text-center col">ມູນຄ່າ</span>
                    </div>
                </td>
                <th scope="col">ຜິດດ່ຽງ</th>
            </tr>
        </thead>
        <tbody id="tableData">
            <?php
            require_once ("./database/TablePrintPDF.php");
            ?>
        </tbody>
    </table>
    <div class="d-flex gap-2">
        <button class="btn btn-warning btn-lg w-100" id="btnback">
            <i class="bi bi-arrow-left-circle-fill"></i> ກັບຄືນ
        </button>
        <button class="btn btn-primary btn-lg w-100" id="btnPrint">
            <i class="bi bi-printer-fill"></i> ປີ້ນໃບສັງລວມການຂາຍ
        </button>
    </div>
</div>
<script>
    $("#btnPrint").click(() => {
        window.print();
    });
    $("#btnback").click(() => {
        history.back();
    });
</script>