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
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table td.cell {
                background-color: blue !important;
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
    <div id="printpdf">
        <div id="pdftitle" class="fs-5 text-center pb-2">

        </div>
        <table class="table table-sm table-bordered" id="tbsales">
            <thead>
                <tr class="text-center align-middle">
                    <td scope="col" rowspan="2">ລຳດັບ</td>
                    <td scope="col" rowspan="2">ແຂວງ</td>
                    <td scope="col" rowspan="2">ໜ່ວຍ</td>
                    <td scope="col" rowspan="2">ລະຫັດຜູ້ຂາຍ</td>
                    <td scope="col" colspan="0" id="colDate">
                        ວັນທີ່ບໍ່ເປີດຂາຍ
                    </td>
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
    });

    $("#btnsave").on("click", async () => {
        const { PDFDocument, rgb } = PDFLib;
        const pdfDoc = await PDFDocument.create();
        const fontkit = window.fontkit;
        try {
            const fonturl = './font/PhetsarathOT.ttf';
            const fontBytes = await fetch(fonturl).then((res) => res.arrayBuffer());
            pdfDoc.registerFontkit(fontkit)
            const customFont = await pdfDoc.embedFont(fontBytes);

            // Define A4 size with margin
            const pageWidth = 841.89;
            const pageHeight = 595.28;
            const margin = 2;
            const usableWidth = pageWidth - 2 * margin;
            const usableHeight = pageHeight - 2 * margin;
            const A4 = [pageWidth, pageHeight];
            let page = pdfDoc.addPage(A4);

            // Define the table data
            const { headers, datas } = tableArray();
            console.log(datas);
            const header1 = Object.entries(headers[0]);
            const header2 = Object.entries(headers[1]);
            const ColDateWidth = 33 * header2.length - 13;
            //Title
            const text = "ລາຍງານເຄື່ອງທີ່ບໍ່ເປີດຂາຍ";
            const textsize = getTextSize(customFont, text, 18);
            const startY = pageHeight - textsize.height;
            const endX = pageWidth / 2 - textsize.width / 2 + margin / 2;

            page.drawText(text, {
                x: endX,
                y: startY - 10,
                size: 18,
                font: customFont,
            });
            //Header
            createRow(page, rgb, 10, -50, "ລຳດັບ", 5, 9, customFont, 20, 14.3, "center", "center");
            createRow(page, rgb, 43, -50, "ແຂວງ", 5, 9, customFont, 65, 14.3, "center", "center");
            createRow(page, rgb, 121, -50, "ໜ່ວຍ", 5, 9, customFont, 65 + 20, 14.3, "center", "center");
            createRow(page, rgb, 199 + 20, -50, "ລະຫັດຜູ້ຂາຍ", 5, 9, customFont, 65 - 20, 14.3, "center", "center");
            createRow(page, rgb, 277, -50, "ວັນທີ່ບໍ່ເປີດຂາຍ", 5, 9, customFont, ColDateWidth, -5, "center", "center");
            let colwitdth = 277;
            header2.forEach((date, index) => {
                createRow(page, rgb, colwitdth, -68, date[1], 5, 6, customFont, 20, -2, "center", "center");
                colwitdth += 33;
            });

            //datas
            let rowHight = -87.5;
            datas.forEach((row, index) => {
                if (rowHight <= -475 - 92) {
                    page = pdfDoc.addPage(A4);
                    rowHight = -25;
                }
                const values = Object.entries(row);
                let rowSize = values.length;
                if (index != datas.length - 1) {
                    createRow(page, rgb, 10, rowHight, row[0], 5, 8, customFont, 20, -5, "center", "center");
                    createRow(page, rgb, 43, rowHight, row[1], 5, 8, customFont, 65, -5, "center", "center");
                    createRow(page, rgb, 121, rowHight, row[2], 5, 8, customFont, 65 + 20, -5, "center", "center");
                    createRow(page, rgb, 199 + 20, rowHight, row[3], 5, 8, customFont, 65 - 20, -5, "center", "center");
                    let colwitdth = 277;
                    for (let i = 4; i < rowSize; i++) {
                        let bg = row[i] == "0" ? [0.5, 1, 0.5] : [1, 0.5, 0.5];
                        if (i != rowSize - 1) {
                            createRow(page, rgb, colwitdth, rowHight, row[i], 5, 8, customFont, 20, -5, "center", "center", bg);
                        } else {
                            createRow(page, rgb, colwitdth, rowHight, row[i], 5, 8, customFont, 20, -5, "center", "center", [0.5, 0.5, 1]);
                        }
                        colwitdth += 33;
                    }
                } else {
                    createRow(page, rgb, 10, rowHight, row[0], 5, 8, customFont, 254, -5, "center", "center");
                    let colwitdth = 277;
                    for (let i = 1; i <= rowSize - 1; i++) {
                        createRow(page, rgb, colwitdth, rowHight, row[i], 5, 8, customFont, 20, -5, "center", "center");
                        colwitdth += 33;
                    }
                }
                rowHight -= 17.5;
                //signature
                if (datas.length - 1 == index) {

                }
            });

            const signature1 = "ຫົວໜ້າສາຂາ";
            const signature1size = getTextSize(customFont, text, 10).width;
            const signature2 = "ເຊັນຜູ້ຕິດຕາມ";
            const signature2size = getTextSize(customFont, text, 10).width;
            const sigposition = pageWidth / 4;
            page.drawText(signature1, {
                x: sigposition - signature1size / 2,
                y: pageHeight - Math.abs(rowHight) - 5,
                size: 10,
                font: customFont,
            });

            page.drawText(signature2, {
                x: sigposition * 3 - signature2size / 2,
                y: pageHeight - Math.abs(rowHight) - 5,
                size: 10,
                font: customFont,
            });

            // createRow(page, rgb, 10, -97.6 - 23.8, "ລຳດັບ", 5, 10, customFont, 20, 0, "center", "center");
            const pdfBytes = await pdfDoc.save();
            // Download the PDF (assuming you have this logic)
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = url;
            const unittext = $('#cbUnit option:selected').text();
            link.download = `ລາຍງານເຄື່ອງບໍ່ເປີດຂາຍ ໜ່ວຍ${unittext}.pdf`;
            link.click();

            URL.revokeObjectURL(url);
        } catch (error) {
            console.error("Error creating PDF:", error);
        }
    });

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
            amount += col;
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

    const tableArray = () => {
        const tableHeader = [];
        const tableData = [];
        $(`#tbsales thead tr`).each(function (rowIndex, row) {
            const rowData = {};
            $(row).find('td').each(function (cellIndex, cell) {
                rowData[(cellIndex)] = $(cell).text().trim();
            });
            tableHeader.push(rowData);
        });

        $(`#tbsales tbody tr`).each(function (rowIndex, row) {
            const rowData = {};
            $(row).find('td').each(function (cellIndex, cell) {
                rowData[(cellIndex)] = $(cell).text().trim();
            });
            tableData.push(rowData);
        });

        return { headers: tableHeader, datas: tableData };
    }

    const getTextSize = (customFont, text, size) => {
        const textWidth = customFont.widthOfTextAtSize(text, size)
        const textHeight = customFont.heightAtSize(size) - size + 2
        return { width: textWidth, height: textHeight, text: text, fontSize: size, font: customFont }
    }

    const createRow = (page, rgb, x = 0, y = 0, text, textWidth, fontsize, customFont, lineW = 0, lineH = 0, textAlignH = "start", textAlignV = "start", background = [1, 1, 1]) => {
        const { width, height } = page.getSize();
        const textData = getTextSize(customFont, text, fontsize);
        const pading = 8;
        const lineY = y + height - textData.height - lineH;
        const lineWidth = textWidth + pading + lineW;
        const lineHeight = textData.height + lineH + pading * 2;
        let positionX = x;
        let positionY = lineY + (lineH / 2 + textData.height / 2) * 2 + pading / 2;
        switch (textAlignH) {
            case 'start':
                positionX = x + pading / 2;
                break;
            case 'center':
                positionX = x + lineWidth / 2 - textData.width / 2;
                break;
            case 'end':
                positionX = x + (lineWidth / 2 - textData.width / 2) * 2 - pading / 2;
                break;
            default:
                positionX = x + pading / 2;
                break;
        }

        switch (textAlignV) {
            case "start":
                positionY = lineY + (lineH / 2 + textData.height / 2) * 2 + pading / 2 - 2;
                break;
            case "center":
                positionY = lineY + lineH / 2 + textData.height / 2 + pading / 2;
                break;
            case "end":
                positionY = lineY + pading / 2;
                break;
            default:
                positionY = lineY + (lineH / 2 + textData.height / 2) * 2 + pading / 2;
                break;
        }

        page.drawRectangle({
            x: x,
            y: lineY,
            width: lineWidth,
            height: lineHeight,
            borderColor: rgb(0, 0, 0),
            color: rgb(background[0], background[1], background[2]),
            borderWidth: 1,
        });

        page.drawText(text, {
            x: positionX,
            y: positionY,
            size: fontsize,
            font: customFont,
        });



        return { width: lineWidth, height: lineHeight, y: y + height - textData.height };
    }

</script>