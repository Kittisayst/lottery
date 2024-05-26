<script>
    $(function () {
        $("#datestart").datepicker({
            dateFormat: "dd/mm/yy"
        });
        $("#dateend").datepicker({
            dateFormat: "dd/mm/yy"
        });
    });
</script>
<div class="content">
    <style>
        .no-print {
            display: none;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 1mm !important;
            }

            * {
                font-family: "Phetsarath OT", sans-serif !important;
                padding: 0 !important;
            }

            #alert_title {
                font-size: 14pt !important;
            }

            table {
                font-size: 9pt !important;
            }

            tbody {
                font-size: 8pt !important;
            }

            #colDateValue td {
                font-size: 8pt !important;
                padding: 0px !important;
            }

            .pt-sm {
                font-size: 8pt !important;
            }

            .hindPrint {
                display: none !important;
            }

            #navid {
                display: none !important;
            }

            footer {
                display: none !important;
            }
        }
    </style>
    <?php
    require_once ("./views/Alert.php");
    ?>
    <div class="d-flex justify-content-between w-100 mb-3 bg-light py-3 hindPrint">
        <form class="d-flex justify-content-center align-items-center gap-2 me-auto ms-auto" id="frmMachine">
            <div>
                <label for="datestart" class="form-label">ວັນທີເລີ່ມ</label>
                <input type="text" id="datestart" name="dateStart" class="form-control" placeholder="ວັນທີເລີ່ມ"
                    autocomplete="off" required>
            </div>
            <div class="me-5">
                <label for="dateend" class="form-label">ວັນທີສີ້ນສຸດ</label>
                <input type="text" id="dateend" name="lotDate" class="form-control" placeholder="ວັນທີສີ້ນສຸດ"
                    autocomplete="off" required>
            </div>
            <div class="">
                <label for="cbProvince" class="form-label">ແຂວງ</label>
                <select class="form-select" name="provinceID" id="cbProvince" required>
                    <?php include_once "./database/Province_Options.php"; ?>
                </select>
            </div>
            <div class="">
                <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                <select class="form-select" name="unitid" id="cbUnit">
                    <?php include_once "./database/unit_Option.php"; ?>
                </select>
            </div>
            <div class="mt-auto">
                <button type="submit" class="btn btn-primary" id="btnshow">
                    <i class="bi bi-search"></i> ສະແດງ
                </button>
            </div>
        </form>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-danger mt-auto" id="btnsave" disabled><i
                    class="bi bi-file-pdf-fill"></i>
                Save</button>
            <button type="button" class="btn btn-success mt-auto" id="btnprint" disabled><i
                    class="bi bi-printer-fill"></i>
                Print</button>
        </div>
    </div>

    <table class="table table-sm table-bordered table-striped" id="tbsales">
        <thead>
            <tr class="text-center align-middle">
                <th scope="col" rowspan="2">ລຳດັບ</th>
                <th scope="col" rowspan="2">ແຂວງ</th>
                <th scope="col" rowspan="2">ໜ່ວຍ</th>
                <th scope="col" rowspan="2">ລະຫັດຜູ້ຂາຍ</th>
                <th scope="col" colspan="0" id="colDate">
                    ວັນທີ່ບໍ່ເປີດຂາຍ
                </th>
            </tr>
            <tr id="colDateValue">

            </tr>
        </thead>
        <tbody id="tableData">

        </tbody>
    </table>
    <div class="d-flex justify-content-around" id="Signature">
        <span>ຫົວໜ້າສາຂາ</span>
        <span>ເຊັນຜູ້ຕິດຕາມ</span>
    </div>
</div>

<script>
    const lotNos = [];
    const unitCodes = [];

    $("#frmMachine").on("submit", async (e) => {
        e.preventDefault();
        $("#tableData").empty();
        $("#colDate").attr("colspan", "0");
        $("#colDateValue").empty();
        const frmData = $("#frmMachine").serializeArray();
        const unitID = frmData[3].value;
        if (unitID != "0") {
            const lotDatas = await getLotOfDate(frmData[0].value, frmData[1].value);
            const unitData = await getUnitData(unitID);
            createTableData(unitData, lotDatas);
            $("#btnsave").attr("disabled", false);
            $("#btnprint").attr("disabled", false);
        } else {
            Swal.fire({
                title: "ຂໍ້ມູນໜ່ວຍ",
                text: "ກະລຸນາເລືອກໜ່ວຍ",
                icon: "warning"
            });
        }
    });

    $("#btnprint").on("click", () => {
        window.print();
    })

    const getLotOfDate = async (dateStart, dateEnd) => {
        console.log(dateStart, dateEnd);
        const res = await fetch(`./api/LotteryAPI.php?api=getlotbydate&datestart=${dateStart}&dateend=${dateEnd}`);
        const jdata = await res.json();
        return jdata.data;
    }

    const getUnitData = async (unitID) => {
        const res = await fetch(`./api/sellCodeAPI.php?api=getunitprovinceall&id=${unitID}`);
        const jdata = await res.json();
        return jdata.data;
    }

    const createTableData = async (unitData, lotData) => {
        const unitCode = unitData.map(item => item.machineCode);
        const pdfDatabyLotNo = await Promise.all(lotData.map(async (lot, index) => {
            const data = await getSavePDFByLotteryNo(lot.lotteryNo);
            return { column: index, values: data, date: dateFormat(lot.lotDate) };
        }));

        unitData.forEach((unit, index) => {
            $("#tableData").append($(`
                    <tr class="text-center">
                        <td>${index + 1}</td>
                        <td>${unit.pname}</td>
                        <td>${unit.unitName}</td>
                        <td>${unit.machineCode}</td>
                    </tr>`));
        });

        const createColumns = [];
        pdfDatabyLotNo.forEach(lot => {
            const colvalues = [];
            unitCode.forEach((code, idx) => {
                const isActive = lot.values.includes(code);
                if (isActive) {
                    colvalues.push({ code: code, value: 0 });
                } else {
                    colvalues.push({ code: code, value: 1 });
                }
            });
            createColumns.push({ index: lot.column, lotdate: lot.date, columns: colvalues });
        });

        const colTotal = [];
        const sumMachine = [];
        createColumns.forEach((data, mindex) => {
            //ສະແດງວັນທີ
            $("#colDate").attr("colspan", createColumns.length);
            $('#colDateValue').append($(`<td class="text-center" style="font-size: smaller;">${data.lotdate}</td>`));
            //ສະແດງຈຳນວນຂາຍ
            let sum = 0;
            sumMachine.push(data.columns.map(item => item.value));
            data.columns.forEach((item, index) => {
                var row = $('#tableData tr').eq(index);
                const rowBG = item.value == 0 ? "bg-success" : "bg-danger";
                row.append($(`<td class="col text-center ${rowBG} pt-sm" style="font-size: smaller;">${item.value}</td>`));
                sum += item.value;
            });
            colTotal.push(sum);
        });

        let strColTotal = "";
        let amount = 0;
        colTotal.forEach(col => {
            strColTotal += `<td>${col}</td>`;
            amount+=col;
        });
        //ລວມ
        $("#tableData").append($(`<tr class="text-center" id="rowAmount"><td colspan="4">ລວມ</td>${strColTotal}<td>${amount}</td></tr>`));
        //ລວມແຕ່ລະໜ່ວຍ
        const columnSums = [];
        for (let col = 0; col < sumMachine[0].length; col++) {
            let sum = 0;
            for (let row = 0; row < sumMachine.length; row++) {
                sum += sumMachine[row][col];
            }
            columnSums.push(sum);
        }
        //ສະແດງຫ້ອງລ່ວມແຕ່ລະໜ່ວຍ
        $("#colDate").attr("colspan", createColumns.length + 1);
        $('#colDateValue').append($(`<td class="text-center" style="font-size: smaller;">ລວມ</td>`));
        let sumUnit = 0;
        columnSums.forEach((sum, index) => {
            var row = $('#tableData tr').eq(index);
            row.append($(`<td class="col text-center pt-sm bg-info" style="font-size: smaller;">${sum}</td>`));
            sumUnit += sum;
        });
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

    const getSavePDFByLotteryNo = async (lotNo) => {
        const res = await fetch(`./api/SalePDFAPI.php?api=getbylotteryno&lotno=${lotNo}`);
        const jdata = await res.json();
        if (jdata.state) {
            const data = jdata.data[0]['pdfData'];
            const convertJson = JSON.parse(data);
            const newData = convertJson.map(item => item.machineCode);
            return newData;
        } else {
            return [];
        }
    }

    const dateFormat = (dateStr) => {
        const dateParts = dateStr.split('-');
        const formattedDate = dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];
        return formattedDate;
    }
</script>