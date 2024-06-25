<style>
    body {
        padding: 4rem;
    }

    @media print {
        @page {
            size: A4 landscape !important;
            margin: 2mm !important;
            padding: 0 !important;
            /* Adjust margins as needed */
        }

        body {
            font-size: 10pt;
            margin-top: 0;
            padding: 0;
        }

        .navbar {
            display: none;
        }

        #formsearch {
            display: none;
        }

        #navid {
            margin: 0;
        }

        #colh {
            font-size: 10pt;
        }

        footer {
            display: none;
        }

        .printhide {
            display: none;
        }
    }
</style>
<script>
    $(function () {
        $("#txtdate").datepicker({
            changeMonth: true,
            dateFormat: 'mm-yy',
            monthNames: ["ມັງກອນ", "ກຸມພາ", "ມີນາ", "ເມສາ", "ພຶດສະພາ", "ມິຖຸນາ", "ກໍລະກົດ", "ສິງຫາ", "ກັນຍາ", "ຕຸລາ", "ພະຈິກ", "ທັນວາ"],
            monthNamesShort: ["ມັງກອນ(1)", "ກຸມພາ(2)", "ມີນາ(3)", "ເມສາ(4)", "ພຶດສະພາ(5)", "ມິຖຸນາ(6)", "ກໍລະກົດ(7)", "ສິງຫາ(8)", "ກັນຍາ(9)", "ຕຸລາ(10)", "ພະຈິກ(11)", "ທັນວາ(12)"],
        });
    });
</script>

<div class="content">
    <?php require_once ("./views/Alert.php") ?>
    <form id="formsearch" method="get">
        <div class="d-flex justify-content-between gap-3 mb-3">
            <div class="me-auto d-flex align-items-center gap-2">
                <label for="txtdate" class="text-nowrap">ປະຈຳເດືອນ</label>
                <input type="text" id="txtdate" name="selectDate" class="form-control" placeholder="ເລືອກເດືອນ"
                    autocomplete="off" required>
            </div>
            <div class="">
                <select class="form-select" name="provinceID" id="cbProvince">
                    <?php
                    include_once ("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div>
                <select class="form-select" name="unitid" id="cbUnit">
                    <?php
                    include_once ("./database/unit_Option.php");
                    ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit" name="search" id="btnsearch"><i
                        class='bx bxs-file-find'></i> ສະແດງ</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center align-middle" id="colh">
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
        const monthYear = formData[0].value.split('-');
        const provinceID = formData[1].value;
        const unitID = formData[2].value;
        if (provinceID != "0") {
            showData(monthYear[0], monthYear[1], provinceID, unitID);
        }
    });

    const showData = async (month, year, provinceid, unitID) => {
        const res = await fetch(`./api/FinancialAPI.php?api=getreportdebt&month=${month}&year=${year}&provinceid=${provinceid}&unitid=${unitID}`);
        const data = await res.json();
        tbdata.empty();
        let sumSales = 0;
        let sumcolpercent = 0;
        let sumCalsales = 0;
        let sumaward = 0;
        let sumAmount = 0;
        let sumetc = 0;
        let sumcash = 0;
        let sumtransfer = 0;
        let sumrepay = 0;
        let sumtax = 0;
        let sumResult = 0;
        if (data.data.length != 0) {
            const rows = await Promise.all(data.data.map(async (value, index) => {
                const Sales = str_number(value['Sales']);
                const percentage = str_number(value['Percentage']);
                const calculatePercentage = (Sales * percentage) / 100;
                const CalSales = Sales - calculatePercentage;
                const Award = str_number(value['Award']);
                const isAward = value['withdrawn'] == '1';
                const Amount = isAward ? CalSales - Award : CalSales;
                await calculateBefoDebt(month, year, provinceid, unitID);
                const payment = await loadPaymnet(value['FinancialID']);
                const Tax = 0;
                const Result = (Amount - payment.sumCash - payment.sumEtc - payment.sumTransfer) + payment.sumrepay + Tax;
                sumSales += Sales;
                sumcolpercent += calculatePercentage;
                sumCalsales += CalSales;
                sumaward += Award;
                sumAmount += Amount;
                sumetc += payment.sumEtc;
                sumcash += payment.sumCash;
                sumtransfer += payment.sumTransfer;
                sumrepay += payment.sumrepay;
                sumtax += Tax;
                sumResult += Result;
                return `
                <tr class="text-center">
                <td>${index + 1}</td>
                <td>${value['lotteryNo']}</td>
                <td>${convertDateFormat(value['lotDate'])}</td>
                <td class="text-end">${myMoney(Sales)}</td>
                <td>${value['Percentage']}</td>
                <td class="text-end">${myMoney(calculatePercentage)}</td>
                <td class="text-end">${myMoney(CalSales)}</td>
                <td class="text-end">${myMoney(Award)}</td>
                <td class="${isAward ? "text-success" : "text-warning"} text-end">${myMoney(Amount)}</td>
                <td class="text-end">${myMoney(payment.sumEtc)}</td>
                <td class="text-end">${myMoney(payment.sumCash)}</td>
                <td class="text-end">${myMoney(payment.sumTransfer)}</td>
                <td class="text-end">${myMoney(payment.sumrepay)}</td>
                <td class="text-end">0</td>
                <td class="text-end">${myMoney(Result)}</td>
                <td>${payment.allcomment}</td>
            </tr>`;
            }));
            tbdata.append(rows.join(''));
            tbdata.append(totalColumns(sumSales, sumcolpercent, sumCalsales, sumaward, sumAmount, sumetc, sumcash, sumtransfer, sumrepay, sumtax, sumResult));
        } else {
            tbdata.append($(`<tr class="text-center"><td colspan="16">ບໍ່ພົບຂໍ້ມູນ</td></tr>`));
        }
    };

    const loadPaymnet = async (financialID) => {
        const res = await fetch(`./api/PaymentlistAPI.php?api=getSumFinancial&FinancialID=${financialID}`);
        const data = await res.json();
        const payment = data.data;
        const sumCash = str_number(payment['sumCash']);
        const sumEtc = str_number(payment['sumEtc']);
        const sumTransfer = str_number(payment['sumTransfer']);
        const sumrepay = str_number(payment['sumrepay']);
        const allcomment = payment['allcomment'];
        return { sumCash: sumCash, sumEtc: sumEtc, sumTransfer: sumTransfer, sumrepay: sumrepay, allcomment: allcomment };
    }

    const totalColumns = (sumSales, sumcolpercent, sumCalsales, sumaward, sumAmount, sumetc, sumcash, sumtransfer, sumrepay, sumtax, sumResult) => {
        return $(`
        <tr class="text-end">
        <td colspan="3" class="text-center">ລວມທັງໝົດ</td>
        <td>${myMoney(sumSales)}</td>
        <td class="text-center">-</td>
        <td>${myMoney(sumcolpercent)}</td>
        <td>${myMoney(sumCalsales)}</td>
        <td>${myMoney(sumaward)}</td>
        <td>${myMoney(sumAmount)}</td>
        <td>${myMoney(sumetc)}</td>
        <td>${myMoney(sumcash)}</td>
        <td>${myMoney(sumtransfer)}</td>
        <td>${myMoney(sumrepay)}</td>
        <td>${myMoney(sumtax)}</td>
        <td>${myMoney(sumResult)}</td>
        <td class="text-center">-</td>
        </tr>`);
    }

    const calculateBefoDebt = async (month, year, provinceid, unitID) => {
        const bfmonth = getBefoMonth(month);
        console.log(bfmonth, year, provinceid, unitID);
        const res = await fetch(`./api/FinancialAPI.php?api=getreportdebt&month=${bfmonth}&year=${year}&provinceid=${provinceid}&unitid=${unitID}`);
        const data = await res.json();
        if (data.data.length != 0) {
            console.log(data.data);
        }
    }

    const getBefoMonth = async (month) => {
        const bfmonth = parseInt(month) - 1;
        return bfmonth == 0 ? 12 : bfmonth;
    }

    $("#cbProvince").on("change", (e) => {
        const provinceID = e.target.value;
        $.get(`./api/unitAPI.php?api=unitbyprovinid&pid=${provinceID}`, (res, err) => {
            $("#cbUnit").html("");
            const units = res.data;
            const optionall = $(`<option value="0">---ໜ່ວຍທັງໝົດ---</option>`);
            $("#cbUnit").append(optionall);
            units.forEach(unit => {
                const option = $(`<option value="${unit['unitID']}">${unit['unitName']}</option>`);
                $("#cbUnit").append(option);
            });
        });
    });


    const str_number = (str) => {
        if (typeof str === 'number') {
            str = str.toString();
        }
        const val = str.replace(/,/g, '');
        const tonumber = parseFloat(val);
        return Number(tonumber);
    }

    const myMoney = (number) => {
        const formattedNumber = number.toLocaleString();
        return formattedNumber;
    }


    function convertDateFormat(dateStr) {
        const [year, month, day] = dateStr.split('-');
        return `${day}/${month}/${year}`;
    }

</script>