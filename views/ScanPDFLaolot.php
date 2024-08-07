<div class="container content">
    <?php
    require_once "./views/Alert.php";
    require_once "./database/ScanPDFTitleLaolot.php";
    ?>

    <div class="d-flex bg-secondary-subtle flex-column align-items-center p-2 mb-1">
        <?= showSalePDFTitle() ?>
        <div class="w-100  mb-3">
            <hr>
        </div>
        <div class="d-flex justify-content-between w-100">
            <form class="d-flex align-items-center gap-2" id="frmshowUint">
                <div class="d-flex align-items-center gap-2">
                    <label for="cbProvince" class="form-label">ແຂວງ</label>
                    <select class="form-select" name="provinceID" id="cbProvince">
                        <?php include_once "./database/Province_Options.php"; ?>
                    </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                    <select class="form-select" name="unitid" id="cbUnit">
                        <?php include_once "./database/unit_Option.php"; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" id="btnshow">
                        <i class="bi bi-search"></i> ສະແດງ
                    </button>
                    <a class="btn btn-info" href="?page=scanpayment&id=<?= $_GET[
                        "id"
                    ] ?>&limit=100&pagination=1"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>
            <div class="btn-group" role="group">
                <button class="btn btn-danger" id="btnsavepdf" disabled>
                    <i class="bi bi-file-earmark-pdf-fill"></i> Save PDF
                </button>
                <button class="btn btn-success" id="btnsaveExcel" disabled>
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Save Excel
                </button>
                <button class="btn btn-primary" id="btnSave" disabled>
                    <i class="bi bi-floppy-fill"></i> ບັນທຶກຂໍ້ມູນ
                </button>
            </div>

        </div>
    </div>

    <table class="table table-bordered table-striped" id="tbsales">
        <thead>
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

        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination" id="pagilist">

            </ul>
        </nav>
    </div>
</div>

<!-- Modal Save all-->
<div class="modal fade" id="modalSaveall" tabindex="-1" aria-labelledby="modalSaveallLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <form class="modal-content" id="saveUnitSalesAll">
            <div class="modal-header">
                <h1 class="modal-title fs-5 w-100 text-center" id="modalSaveallLabel">ບັນທຶກຂໍ້ມູນຍອດຂາຍທັງໝົດ</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container">
                <ol class="list-group list-group-numbered" id="listUnitData">

                </ol>
            </div>
            <div class="modal-footer container">
                <div id="progressSaveall" class="mb-3 w-100">

                </div>
                <button type="submit" class="btn btn-primary w-100"
                    id="btnScanUnit">ກວດສອບຂໍ້ມູນການຂາຍແຕ່ລະໜ່ວຍ</button>
                <button type="button" class="btn btn-primary w-100" id="btnsaveall">ບັນທຶກຂໍ້ມູນ</button>
            </div>
        </form>
    </div>
</div>

<script>
    const loadDefaultData = async () => {
        $('#tableData').html(`
            <tr class="text-center">
                <td colspan='7'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງໂຫຼດຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
        const res = await fetch("./api/sellCodeLaolot.php?api=getall");
        const datajson = await res.json();
        const respdf = await fetch("./database/ReaderSalePDFLaolotTable.php?id=<?= $_GET["id"] ?>");
        const respdfData = await respdf.json();
        const machineDatas = datajson.data;
        const conData2json = JSON.parse(respdfData.data);
        const pdfDatas = conData2json.map(item => typeof item === "string" ? JSON.parse(item) : item);
        const pdfCode = pdfDatas.map(item => item['machineCode']);
        $("#tableData").html("");

        let Sales = 0;
        let Award = 0;
        let Price = 0;
        let Amount = 0;
        machineDatas.forEach((item, index) => {
            let lotdata = [];
            //
            if (pdfCode.includes(Number(item['machineCode']))) {
                lotdata = pdfDatas.find(data => data['machineCode'] == Number(item['machineCode']));
                CreateTable(lotdata, index);
            } else {
                lotdata = { "machineCode": item['machineCode'], Sales: '0', Award: '0', Percentage: '0', Price: '0', Amount: '0' };
                CreateTable(lotdata, index);
            }
            //ຄິດໄລ່ລວມເງິນ
            const sum = convertMoney(lotdata);
            Sales += sum.Sales;
            Award += sum.Award;
            Price += sum.Price;
            Amount += sum.Amount;
        });
        CreateRowTotal(Sales, Award, Price, Amount);
        if (machineDatas.length >= 500) {
            const rowviews = 500;
            showCurrentPage(rowviews, 1);
            PaginationEvents(machineDatas, rowviews);
        } else {
            $('#tableData tr').show();
        }
    }

    //ໂຫຼດຂໍ້ມູນທັງໝົດ
    loadDefaultData();

    //ສະຕາຕະລາງ
    const CreateTable = (data, index) => {
        const col = $(`<tr class="text-end">
            <td class='text-center'>${index + 1}</td>
            <td class='text-center'>${data['machineCode']}</td>
            <td>${data['Sales']}</td>
            <td>${data['Award']}</td>
            <td class='text-center col-1'>${data['Percentage']}</td>
            <td class='col-1'>${data['Price']}</td>
            <td>${data['Amount']}</td>
        </tr>`);
        $("#tableData").append(col);
        col.hide();
    }

    //ສ້າງຫ້ອງລວມເງິນ
    const CreateRowTotal = (Sales, Award, Price, Amount) => {
        const col = $(`<tr class='text-end'>
                <td colspan='2' class='text-center'>ລ່ວມທັງໝົດ</td>
                <td>${Sales.toLocaleString()}</td>
                <td>${Award.toLocaleString()}</td>
                <td class='text-center'>-</td>
                <td>${Price.toLocaleString()}</td>
                <td>${Amount.toLocaleString()}</td>
            </tr>`);
        $("#tableData").append(col);
        col.hide();
        setTimeout(() => {
            isShowButton();
        }, 1000);
    }

    //ຄົ້ນຫາຕາມໜ່ວຍ
    $("#frmshowUint").submit((e) => {
        e.preventDefault();
        $("#btnsavepdf").prop("disabled", true);
        $("#btnsaveExcel").prop("disabled", true);
        $("#btnSave").prop("disabled", true);
        const frm = $("#frmshowUint").serializeArray();
        LoadMachineCode(frm[1].value);
    });

    //ສະແດງຂໍ້ມູນໜ່ວຍທັງມີການຂາຍ ແລະ ບໍ່ຂາຍ
    const LoadMachineCode = async (unitID) => {
        const res = await fetch(`./api/sellCodeLaolot.php?api=getbyunitid&id=${unitID}`);
        const resjson = await res.json();
        const respdfData = await fetch(`./database/ReaderSalePDFLaolotTable.php?id=<?= $_GET["id"] ?>&unitID=${unitID}`);
        const resjsonpdf = await respdfData.json();
        const converPDFjson = JSON.parse(resjsonpdf.data);
        const readPDF = converPDFjson.map(item => typeof item === "string" ? JSON.parse(item) : item);
        const pdfCode = readPDF.map(item => item['machineCode']);
        $("#tableData").html("");
        let Sales = 0;
        let Award = 0;
        let Price = 0;
        let Amount = 0;
        resjson.data.forEach((item, index) => {
            let lotdata = [];
            //
            if (pdfCode.includes(Number(item['machineCode']))) {
                lotdata = readPDF.find(data => data['machineCode'] == Number(item['machineCode']));
                CreateTable(lotdata, index);
            } else {
                lotdata = { "machineCode": item['machineCode'], Sales: '0', Award: '0', Percentage: '0', Price: '0', Amount: '0' };
                CreateTable(lotdata, index);
            }
            //ຄິດໄລ່ລວມເງິນ
            const sum = convertMoney(lotdata);
            Sales += sum.Sales;
            Award += sum.Award;
            Price += sum.Price;
            Amount += sum.Amount;
        });
        CreateRowTotal(Sales, Award, Price, Amount);
        $("#pagilist").html("");
        if (resjson.data.length >= 500) {
            const rowviews = 500;
            showCurrentPage(rowviews, 1);
            PaginationEvents(resjson.data, rowviews);
        } else {
            $('#tableData tr').show();
        }
    }

    const isShowButton = () => {
        var rowCount = $('#tableData tr').length;
        $("#btnsavepdf").prop("disabled", rowCount <= 1);
        $("#btnsaveExcel").prop("disabled", rowCount <= 1);
        $("#btnshow").prop("disabled", rowCount <= 1);
        $("#btnSave").prop("disabled", rowCount <= 1);
    }

    //Export Excel
    $("#btnsaveExcel").on("click", () => {
        const Month = new Date().getMonth() + 1;
        const table = document.getElementById("tbsales");
        const workbook = XLSX.utils.table_to_book(table, {
            sheet: "ຍອດຂາຍ ແລະ ຖືກລາງວັນ"
        });
        XLSX.writeFile(workbook, `ຍອດຂາຍ ແລະ ຖືກລາງວັນ ${Month} ${jdateTimeNow()}.xlsx`);
    });

    //Export PDF file
    $("#btnsavepdf").on("click", () => {
        createPDF();
    });

    //Save PDF Data
    $("#btnSave").on("click", () => {
        handleSaveData();
    });

    const handleSaveData = () => {
        const provinceText = $("#cbProvince option:selected").text();
        const unittext = $('#cbUnit option:selected').text();
        const lotdate = $("#lotdate").text();
        // ຍອດຂາຍ ແຂວງ​ໄຊຍະບູລີ ໜ່ວຍ​ ທ.​ເປ​ ວັນທີ່.​ 19/04/2024
        const commentText = `ຍອດຂາຍ ${provinceText} ໜ່ວຍ ${unittext} ວັນທີ່ ${lotdate}`;
        Swal.fire({
            position: "top",
            html: `
            <div class="mb-4 p-3">
                <h4 class="fw-bold">${commentText}</h4>
            </div>
            <form class="p-1" id="frmSave">
                <div class="mb-3">
                    <label for="cblot" class="form-label w-100 text-start">ງວດທີ</label>
                    <select class="form-select" name="loteryID" id="cblot" required>
                        <?php require_once "./database/LotteryOption.php"; ?>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="txtcomment" class="form-label w-100 text-start">ໝາຍເຫດ</label>
                    <textarea class="form-control" id="txtcomment" name="comment" rows="3" placeholder="ໝາຍເຫດ"></textarea>
                </div>
                <div>
                    <button class="btn btn-primary w-100" type="submit">ບັນທຶກຂໍ້ມູນ PDF</button>
                </div>
            </form>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            focusCancel: false,
            allowOutsideClick: false
        });

        $("#frmSave").submit((e) => {
            e.preventDefault();
            const frm = $("#frmSave").serializeArray();
            const unitID = $('#cbUnit option:selected').val();
            const datas = {
                "salePDFID": <?= $_GET["id"] ?>,
                "unitID": unitID,
                "lotteryID": frm[0].value,
                "comment": frm[1].value,
                "pdfData": JSON.stringify(createPDFData())
            };
            if (unitID == "0") {
                Swal.close();
                saveUnitAll(datas);
            } else {
                console.log("save unit");
            }
        });
    }

    const saveUnitAll = async (datas) => {
        $("#modalSaveall").modal('show');
        $("#btnsaveall").hide();
        const SaveDatas = [];
        //ສະແດງແຂວງທີ່ບັນທຶກ
        const resProvinces = await fetch("./api/ProvinceAPI.php?api=getprovinces");
        const provincesjson = await resProvinces.json();
        const provinceData = provincesjson.data;
        $("#listUnitData").html("");
        //ສະແດງໜ່ວຍ
        provinceData.forEach(async (province) => {
            const resunit = await fetch(`./api/unitAPI.php?api=getUnitsbyProvinID&provinceID=${province.pid}`);
            const unitsjson = await resunit.json();
            const countUnit = unitsjson.data.length;
            const provincelist = $(`
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                        <div class="fw-bold">${province.pname}</div>
                            <div id="lp${province.pid}">
                            
                            </div>
                        </div>
                        <span class="badge text-bg-primary rounded-pill">${countUnit}</span>
                    </li>`);
            $("#listUnitData").append(provincelist);
            const createarrsave = { provincelistID: `lp${province.pid}`, units: unitsjson.data };
            //ຈຳນວນໜ່ວຍທີ່ຕ້ອງບັນທຶກ
            SaveDatas.push(createarrsave);
        });

        //ປຸ່ມບັນທຶກການຂາຍທັງໝົດ
        $("#saveUnitSalesAll").on("submit", async (e) => {
            e.preventDefault();
            $("#btnScanUnit").attr("disabled", true);
            const financials = [];
            // Using Promise.all to wait for all async operations to finish before proceeding
            await Promise.all(SaveDatas.map(async (data, idx) => {
                // Using for loop instead of forEach to handle async/await properly
                for (const unit of data.units) {
                    $(`#${data.provincelistID}`).html(`<span class='text-warning'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ກຳລັງກວດສອບຂໍ້ມູນຈາກ PDF
                </span>`);

                    // ສະແດງລະຫັດການຂາຍ
                    const resUnitMachine = await fetch(`./api/sellCodeLaolot.php?api=getbyunitid&id=${unit.unitID}`);
                    const unitMachineJson = await resUnitMachine.json();

                    //ຂໍ້ມູນ Excelການຂາຍ
                    const resPDFSaleUnit = await fetch(`./database/ReaderSalePDFLaolotTable.php?id=<?= $_GET["id"] ?>&unitID=${unit.unitID}`);
                    const resPDFSaleUnitJson = await resPDFSaleUnit.json();
                    const pdfSale2Json = JSON.parse(resPDFSaleUnitJson.data);

                    if (pdfSale2Json) {
                        //ລະຫັດການຂາຍ
                        const ConvertItemPDF = pdfSale2Json.map(item => typeof item === 'string' ? JSON.parse(item) : item);
                        const machinecodes = ConvertItemPDF.map(item => item['machineCode']);
                        //ຈຳນວນເງິນລວມຂອງແຕ່ລະໜ່ວຍ
                        let Sales = 0;
                        let Award = 0;

                        unitMachineJson.data.forEach(value => {
                            let lotdata = {};
                            if (machinecodes.includes(Number(value['machineCode']))) {
                                lotdata = ConvertItemPDF.find(data => data['machineCode'] == Number(value['machineCode']));
                                //ຄິດໄລ່ການຂາຍລວມຂອງຕໍ່ໜ່ວຍ
                                const calSales = convertMoney(lotdata);
                                Sales += calSales.Sales;
                                Award += calSales.Award;
                            }
                        });

                        const today = new Date();
                        const year = today.getFullYear();
                        const month = String(today.getMonth() + 1).padStart(2, '0');
                        const day = String(today.getDate()).padStart(2, '0');
                        const formattedDate = `${year}-${month}-${day}`;
                        const values = {
                            "unitID": unit.unitID,
                            "lotteryID": datas.lotteryID,
                            "Sales": Sales,
                            "Percentage": unit.Percentage,
                            "Award": Award,
                            "Awardno": "0",
                            "SaveDate": formattedDate,
                            "userID": <?= $_COOKIE['user'] ?>
                        };
                        //ບັນທຶກການຂາຍທັງໝົດແຕ່ລະໜ່ວຍ
                        financials.push(values);
                    }
                    $(`#${data.provincelistID}`).html(`<span class='text-success'>ກວດສອບຂໍ້ມູນການຂາຍແຕ່ລະໜ່ວຍສຳເລັດ</span>`);
                }
            }));


            $("#btnsaveall").show();
            $("#btnScanUnit").hide();
            $("#btnsaveall").click(() => {
                $("#btnsaveall").attr("disabled", true);
                console.log(financials);
                financials.forEach((value, index) => {
                    setTimeout(() => {
                        const url = `./api/FinancialLaolotAPI.php?api=create&id=${value.lotteryID}`;
                        console.log(url);
                        $.post(url, value, (res) => {
                            console.log(res);
                        });
                        const strProgress = progressSaveAll(financials.length, index);
                        $("#progressSaveall").html(strProgress);
                        if (financials.length - 1 == index) {
                            Swal.fire({
                                text: "ບັນທຶກຂໍ້ມູນການຂາຍທັງໝົດສຳເລັດ",
                                icon: "success"
                            }).then(() => location.reload());
                        }
                    }, 100 * index);
                });
            });
        });
    }

    // const saveUnitData = (datas) => {
    //     $.post(`./api/PDFDataAPI.php?api=create`, datas, (res) => {
    //         if (res.state) {
    //             Swal.fire({
    //                 title: res.message,
    //                 icon: "success",
    //                 showCancelButton: true,
    //                 confirmButtonText: "ປີ້ນໃບລາຍງານ ການຂາຍ",
    //                 cancelButtonText: "ຄົ້ນຫາຂໍ້ມູນ PDF ໜ້ານີ້ຕໍ່"
    //             }).then((result) => {
    //                 if (result.isConfirmed) {
    //                     location.href = `?page=printsalepdf&id=${res.data}`;
    //                 } else {
    //                     Swal.close();
    //                 }
    //             });
    //         } else {
    //             Swal.fire({
    //                 title: res.message,
    //                 icon: res.data
    //             });
    //         }
    //     });
    // }

    const createPDFData = () => {
        const arrayData = tableToArray("tbsales");
        // Initialize an array to store processed data
        const values = arrayData.map(item => ({
            machineCode: item[1],
            Sales: item[2],
            Award: item[3],
            Percentage: item[4],
            Price: item[5],
            Amount: item[6]
        }));
        values.pop();
        return values;
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

    //ສະແດງໂຫຼດ
    const showCurrentPage = (pageSize, currentPage) => {
        $('#tableData tr').hide();
        startIndex = (currentPage - 1) * pageSize;
        endIndex = startIndex + pageSize;
        $('#tableData tr').slice(startIndex, endIndex).show();
    }

    //ເຫດການໂຫຼດ
    function PaginationEvents(tableData, buttons) {
        const countButton = tableData.length / buttons;
        $("#pagilist").append($(`<li class="page-item"><a class="page-link" href="#" data-page="1">⬅️</a></li>`));
        for (var i = 0; i <= countButton; i++) {
            if (i == 0) {
                $("#pagilist").append($(`<li class="page-item"><a class="page-link active" href="#" data-page="${i + 1}">${i + 1}</a></li>`));
            } else {
                $("#pagilist").append($(`<li class="page-item"><a class="page-link" href="#" data-page="${i + 1}">${i + 1}</a></li>`));
            }
        }
        $("#pagilist").append($(`<li class="page-item"><a class="page-link" href="#" data-page="${countButton + 1}">➡️</a></li>`));

        $('.pagination a').click(function (e) {
            e.preventDefault();
            var newPage = $(this).data('page');
            $('.pagination a').removeClass('active');
            $(this).addClass('active');
            showCurrentPage(buttons, newPage);
        });
    }

    const tableToArray = (tableid) => {
        // Define an empty array to store the table data
        var tableData = [];
        // Iterate over each row in the tbody
        $(`#${tableid} tbody tr`).each(function (rowIndex, row) {
            // Define an empty object to store the cell data for each row
            var rowData = {};

            // Iterate over each cell in the row
            $(row).find('td').each(function (cellIndex, cell) {
                // Add the cell data to the rowData object
                rowData[(cellIndex)] = $(cell).text().trim();
            });
            // Push the rowData object to the tableData array
            tableData.push(rowData);
        });
        return tableData;
    }        //ແປງຂໍ້ມູນລວມເງິນ
    const convertMoney = (data) => {
        const Sales = str_number(data['Sales']);
        const Award = str_number(data['Award']);
        const Price = str_number(data['Price']);
        const Amount = str_number(data['Amount']);
        return { "Sales": Sales, "Award": Award, "Price": Price, "Amount": Amount };
    }

    const str_number = (str) => {
        if (typeof str == "string") {
            const val = str.replace(/,/g, '');
            const tonumber = parseFloat(val);
            return Number(tonumber);
        } else {
            return str;
        }
    }

    const myMoney = (number) => {
        const formattedNumber = number.toLocaleString();
        return formattedNumber;
    }

    const jdateTimeNow = () => {
        const currentDate = new Date();
        const day = currentDate.getDate().toString().padStart(2, '0');
        const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
        const year = currentDate.getFullYear();
        const hours = currentDate.getHours().toString().padStart(2, '0');
        const minutes = currentDate.getMinutes().toString().padStart(2, '0');
        const seconds = currentDate.getSeconds().toString().padStart(2, '0');
        const formattedDateTime = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
        return formattedDateTime;
    }

    async function createPDF() {
        const { PDFDocument, rgb } = PDFLib;
        const pdfDoc = await PDFDocument.create();

        const fontkit = window.fontkit;
        try {
            const fonturl = './font/boonhome-400.otf';
            const fontBytes = await fetch(fonturl).then((res) => res.arrayBuffer());
            pdfDoc.registerFontkit(fontkit)
            const customFont = await pdfDoc.embedFont(fontBytes);

            const A4 = [595.28, 841.89];
            let page = pdfDoc.addPage(A4);
            const fontSize = 14;
            const topMargin = 10;
            const leftMargin = 5;
            const linespace = 5;
            const { width, height } = page.getSize();
            const provinceText = $("#cbProvince option:selected").text();
            const unittext = $('#cbUnit option:selected').text();
            const lotno = $("#lot").text();
            const lotdate = $("#lotdate").text();
            const lotNoText = lotno == "" ? "------" : lotno;
            const exportFileName = `ຍອດຂາຍ ໜ່ວຍ ${unittext == "---ໜ່ວຍທັງໝົດ---" ? "ທັງໝົດ" : unittext} ງວດທີ ${lotNoText} ວັນທີ ${lotdate}`;
            const text = exportFileName;
            const textsize = getTextSize(customFont, text, fontSize);
            const startY = height - textsize.height;
            const endX = width / 2 - textsize.width / 2 + leftMargin / 2;

            page.drawText(text, {
                x: endX,
                y: startY - topMargin - 5,
                size: fontSize,
                font: customFont,
            });

            const arrheader = ["ລຳດັບ", "ລະຫັດຜູ້ຂາຍ", "ເລກບິນ", "ຖືກເລກ 1 ໂຕ", "ຖືກເລກ 2 ໂຕ", "ຖືກເລກ 3 ໂຕ", "ຖືກເລກ 4 ໂຕ", "ຖືກເລກ 5 ໂຕ", "ຖືກເລກ 6 ໂຕ", "ລວມ", "ໝາຍເຫດ"];
            let rowspace = -50;
            createRow(page, rgb, 10, -50, "ລຳດັບ", 12, 12, customFont, 20, 20, "center", "center");
            createRow(page, rgb, 50, -50, "ລະຫັດຜູ້ຂາຍ", 12, 12, customFont, 80, 20, "center", "center");
            createRow(page, rgb, 150, -50, "ມູນຄ່າຂາຍໄດ້", 12, 12, customFont, 80, 20, "center", "center");
            createRow(page, rgb, 250, -50, "ມູນຄ່າຖືກລາງວັນ", 12, 12, customFont, 80, 20, "center", "center");
            createRow(page, rgb, 350, -50, "ຜູ້ຂາຍໜ່ວຍ", 12, 12, customFont, 100, 20, "center", "start");
            page.drawText("%    |", {
                x: 350 + 35,
                y: startY - topMargin - 50,
                size: 12,
                font: customFont,
            });
            page.drawText("     ມູນຄ່າ", {
                x: 350 + 55,
                y: startY - topMargin - 50,
                size: 12,
                font: customFont,
            });
            createRow(page, rgb, 470, -50, "ຜິດດ່ຽງ", 12, 12, customFont, 95, 20, "center", "center");
            const arrtable = tableToArray("tbsales");
            const sumrow = arrtable.pop();
            let rowheight = -92;
            arrtable.forEach((lots, index) => {
                if (rowheight <= -730 - 92) {
                    page = pdfDoc.addPage(A4);
                    rowheight = -25;
                }
                createRow(page, rgb, 10, rowheight, lots[0], 12, 12, customFont, 20, 0, "center", "center");
                createRow(page, rgb, 50, rowheight, lots[1], 12, 12, customFont, 80, 0, "center", "center");
                createRow(page, rgb, 150, rowheight, lots[2], 12, 12, customFont, 80, 0, "end", "center");
                createRow(page, rgb, 250, rowheight, lots[3], 12, 12, customFont, 80, 0, "end", "center");
                createRow(page, rgb, 350, rowheight, lots[4], 12, 12, customFont, 40, 0, "center", "start");
                createRow(page, rgb, 350 + 60, rowheight, lots[5], 12, 12, customFont, 40, 0, "center", "start");
                createRow(page, rgb, 470, rowheight, lots[6], 12, 12, customFont, 95, 0, "end", "start");
                rowheight += -22;
            });

            //sumrow
            createRow(page, rgb, 10, rowheight, sumrow[0], 12, 12, customFont, 120, 0, "center", "center");
            createRow(page, rgb, 150, rowheight, sumrow[1], 12, 12, customFont, 80, 0, "end", "center");
            createRow(page, rgb, 250, rowheight, sumrow[2], 12, 12, customFont, 80, 0, "end", "center");
            createRow(page, rgb, 350, rowheight, sumrow[3], 12, 12, customFont, 40, 0, "center", "start");
            createRow(page, rgb, 350 + 60, rowheight, sumrow[4], 12, 12, customFont, 40, 0, "center", "start");
            createRow(page, rgb, 470, rowheight, sumrow[5], 12, 12, customFont, 95, 0, "end", "start");

            // Serialize the PDF document to bytes
            const pdfBytes = await pdfDoc.save();

            // Download the PDF (assuming you have this logic)
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = url;
            link.download = `${exportFileName.replace("/", "-").replace("/", "-")}.pdf`;
            link.click();

            URL.revokeObjectURL(url);
        } catch (error) {
            console.error("Error creating PDF:", error);
        }
    }

    const getTextSize = (customFont, text, size) => {
        const textWidth = customFont.widthOfTextAtSize(text, size)
        const textHeight = customFont.heightAtSize(size) - size + 2
        return { width: textWidth, height: textHeight, text: text, fontSize: size, font: customFont }
    }

    const createRow = (page, rgb, x = 0, y = 0, text, textWidth, fontsize, customFont, lineW = 0, lineH = 0, textAlignH = "start", textAlignV = "start") => {
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

        page.drawText(text, {
            x: positionX,
            y: positionY,
            size: fontsize,
            font: customFont,
        });

        page.drawRectangle({
            x: x,
            y: lineY,
            width: lineWidth,
            height: lineHeight,
            borderColor: rgb(0, 0, 0),
            borderWidth: 1,
        });

        return { width: lineWidth, height: lineHeight, y: y + height - textData.height };
    }

    const progressSaveAll = (size, value) => {
        const progressv = (value / size) * 100;
        const html = `
            <div class="progress w-100" role="progressbar" aria-valuenow="${progressv}" aria-valuemin="0" aria-valuemax="100" style="height: 15px">
                <div class="progress-bar bg-success" style="width: ${progressv}%"></div>
            </div>`;
        return html;
    }

</script>