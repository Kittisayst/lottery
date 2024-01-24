<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <script>
        $(function() {
            $("#datepicker").datepicker();
            $("#datepickerEnd").datepicker();
        });
    </script>
    <form id="frmSearch">
        <div class="mb-3 d-flex justify-content-end gap-2">
            <div class="d-flex align-items-center gap-2">
                <label for="datepicker" class="form-label">ວັນທີ</label>
                <input type="text" id="datepicker" name="startdate" class="form-control">
                <label for="datepickerEnd" class="form-label">ຫາ</label>
                <input type="text" id="datepickerEnd" name="enddate" class="form-control">
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbProvince" class="form-label">ແຂວງ</label>
                <select class="form-select" aria-label="Default select example" name="proviceid" id="cbProvince">
                    <?php
                    include_once("./database/ProvinceSearchOption.php");
                    ?>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                <select class="form-select" aria-label="Default select example" name="unitid" id="cbUnit">
                    <?php
                    include_once("./database/unitOption.php");
                    ?>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class='bx bx-search'></i> ສະແດງ</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered" id="tableReport">
        <thead>
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ງວດທີ</th>
                <th scope="col">ວັນທີ</th>
                <th scope="col">ຍອດຂາຍ</th>
                <th scope="col">ຫັກເບີເຊັນ</th>
                <th scope="col">ລາງວັນ</th>
                <th scope="col">ຍອດເຫຼືອ</th>
            </tr>
        </thead>
        <tbody id="tabledata">
            <tr class="text-center">
                <td colspan="7">..... ຄົ້ນຫາລາຍງານລົງບັນຊີ .....</td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <button class="btn btn-success" id="btnexport"><i class='bx bx-grid'></i> Export Excel</button>
    </div>

</div>
<script src="./script/calculatorlot.js">
</script>
<script>
    const tableData = $("#tabledata");

    const isExport = () => {
        const table = document.getElementById("tableReport");
        var rowCount = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
        $("#btnexport").prop("disabled", rowCount <= 1);
        console.log(rowCount);
    }

    isExport();

    //Export Excel
    $("#btnexport").on("click", () => {
        const table = document.getElementById("tableReport");
        const workbook = XLSX.utils.table_to_book(table, {
            sheet: "ລາຍງານລົງບັນຊີ"
        });
        XLSX.writeFile(workbook, `ລົງບັນຊີບັນຈຳເດືອນ ${Month} ${jdateTimeNow()}.xlsx`);
    });

    const CreateTableReport = (financials) => {
        tableData.html("");
        console.log(financials);
        let sumSales = 0;
        let sumPercent = 0;
        let sumAward = 0;
        let sumTotal = 0;
        financials.forEach((fc, index) => {
            const calculator = calculatorlot(fc.Sales, fc.Percentage, fc.Award);
            sumSales += Number(fc.Sales);
            sumPercent += Number(calculator.percentageMoney);
            sumAward += Number(fc.Award);
            sumTotal += Number(calculator.amoutMoney);
            // console.log(calculator);
            const tr = $("<tr></tr>");
            tr.html(`
            <td class="text-center">${index+1}</td>
            <td class="text-center">${fc.lotteryNo}</td>
            <td class="text-center">${jDateformat(fc.lotDate)}</td>
            <td class="text-end">${jFormatMoney(fc.Sales)}</td>
            <td class="text-end">${jFormatMoney(calculator.percentageMoney)}</td>
            <td class="text-end">${jFormatMoney(fc.Award)}</td>
            <td class="text-end">${jFormatMoney(calculator.amoutMoney)}</td>
            `);
            tableData.append(tr);
        });
        const tr = $("<tr></tr>");
        tr.html(`
            <td class="text-center" colspan="3">ລວມ</td>
            <td class="text-end">${jFormatMoney(sumSales)}</td>
            <td class="text-end">${jFormatMoney(sumPercent)}</td>
            <td class="text-end">${jFormatMoney(sumAward)}</td>
            <td class="text-end">${jFormatMoney(sumTotal)}</td>
            `);
        tableData.append(tr);
        isExport();
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

    $("#frmSearch").on("submit", (e) => {
        e.preventDefault();
        const fromData = $("#frmSearch").serialize();
        $.post(`./api/FinancialAPI.php?api=getReprotFinancialSearch`, fromData, (res) => {
            const financials = res.data;
            if (financials.length > 0) {
                CreateTableReport(financials);
            } else {
                tableData.html(`<tr class="text-center"><td colspan="7">..... ບໍ່ພົບຂໍ້ມູນ.....</td></tr>`);
            }
        });
    })
</script>