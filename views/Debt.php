<script>
    $(function () {
        $("#txtdate").datepicker({
            changeMonth: true,
            dateFormat: 'mm',
            monthNames: ["ມັງກອນ", "ກຸມພາ", "ມີນາ", "ເມສາ", "ພຶດສະພາ", "ມິຖຸນາ", "ກໍລະກົດ", "ສິງຫາ", "ກັນຍາ", "ຕຸລາ", "ພະຈິກ", "ທັນວາ"],
            monthNamesShort: ["ມັງກອນ(1)", "ກຸມພາ(2)", "ມີນາ(3)", "ເມສາ(4)", "ພຶດສະພາ(5)", "ມິຖຸນາ(6)", "ກໍລະກົດ(7)", "ສິງຫາ(8)", "ກັນຍາ(9)", "ຕຸລາ(10)", "ພະຈິກ(11)", "ທັນວາ(12)"],
        });
    });
</script>
<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <form id="formsearch" method="get">
        <div class="d-flex justify-content-between gap-3 mb-3">
            <div class="me-auto d-flex align-items-center gap-2">
                <label for="txtdate" class="text-nowrap">ປະຈຳເດືອນ</label>
                <input type="text" id="txtdate" name="selectDate" class="form-control" placeholder="ເລືອກເດືອນ"
                    autocomplete="off" required>
            </div>
            <div class="">
                <select class="form-select" aria-label="Default select example" name="provinceID" id="cbprovince"
                    required>
                    <?php
                    include ("./database/Province_Options.php");
                    $search = isset($_GET['search']) ? $_GET['search'] : "";
                    ?>
                </select>
            </div>
            <div>
                <input type="search" id="txtsearch" name="search" class="form-control" value="<?= $search ?>"
                    placeholder="ຊື່ໜ່ວຍ/ໄອດີໜ່ວຍ">
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit" name="search" id="btnsearch"><i
                        class='bx bxs-file-find'></i> ສະແດງ</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center align-middle">
                <th scope="col">ລ/ດ</th>
                <th scope="col">ງວດ</th>
                <th scope="col">ງວດວັນທີ</th>
                <th scope="col">ຍອດຂາຍ</th>
                <th scope="col">ເປີເຊັນ</th>
                <th scope="col">ຫັກເປີເຊັນ</th>
                <th scope="col">ຍອດເຫຼືອ</th>
                <th scope="col">ລາງວັນ</th>
                <th scope="col">ຕ້ອງຖອກໃຫ້ບ/ສ</th>
                <th scope="col">ອື່ນໆ</th>
                <th scope="col">ເງິນສົດ</th>
                <th scope="col">ເງິນໂອນ</th>
                <th scope="col">ບໍລິສັດໂອນມາ</th>
                <th scope="col">ຫັກອາກອນລາງວັນ</th>
                <th scope="col" class="text-danger">ຍັງຄ້າງບໍລິສັດ</th>
                <th scope="col" class="d-flex flex-column"><span></span>ວດປໂອນ<span>ທ/ຄ ການຄ້າ</span></th>
            </tr>
        </thead>
        <tbody id="tbdata">

        </tbody>
    </table>
    <div class="d-flex justify-content-around">
        <span class="">ຫົວໜ້າກຸ່ມຕົວແທນ</span>
        <span class="">ແມ່ໜ່ວຍ</span>
        <span class="">ພ/ງບັນຊີ</span>
    </div>
</div>
<script>
    const formsearch = $('#formsearch');
    const tbdata = $('#tbdata');

    formsearch.on("submit", (e) => {
        e.preventDefault();
        const formData = formsearch.serializeArray();

        console.log(formData);
        showData();
    });

    const showData = async () => {
        const res = await fetch("./api/FinancialAPI.php?api=getfinancials");
        const data = await res.json();
        console.log(data);
        data.data.forEach(async (value, index) => {
            const Sales = str_number(value['Sales']);
            const percentage = str_number(value['Percentage']);
            const calculatePercentage = (Sales * percentage) / 100;
            const CalSales = Sales - calculatePercentage;
            const Award = str_number(value['Award']);
            const isAward = value['withdrawn'] == '1' ? true : false;
            const Amount = isAward ? CalSales - Award : CalSales;
            const payment = await loadPaymnet(value['FinancialID']);
            const sumCash = str_number(payment['sumCash']);
            const sumEtc = str_number(payment['sumEtc']);
            const sumTransfer = str_number(payment['sumTransfer']);
            const sumrepay = str_number(payment['sumrepay']);
            const Tax = 0;
            const Result = (Amount - sumCash - sumEtc - sumTransfer) + sumrepay + Tax;
            const row = $(`<tr>
            <td>${index + 1}</td>
            <td>${value['lotteryNo']}</td>
            <td>${value['lotDate']}</td>
            <td>${value['Sales']}</td>
            <td>${value['Percentage']}</td>
            <td>${calculatePercentage}</td>
            <td>${CalSales}</td>
            <td>${Award}</td>
            <td class="${isAward ? "text-success" : "text-warning"}">${Amount}</td>
            <td>${sumEtc}</td>
            <td>${sumCash}</td>
            <td>${sumTransfer}</td>
            <td>${sumrepay}</td>
            <td>0</td>
            <td>${Result}</td>
            <td>${payment['allcomment']}</td>
            </tr>`);
            tbdata.append(row);
        });
    }

    const loadPaymnet = async (financialID) => {
        const res = await fetch(`./api/PaymentlistAPI.php?api=getSumFinancial&FinancialID=${financialID}`);
        const data = await res.json();
        return data.data;
    }

    const str_number = (str) => {
        const val = str.replace(/,/g, '');
        const tonumber = parseFloat(val);
        return Number(tonumber);
    }
</script>