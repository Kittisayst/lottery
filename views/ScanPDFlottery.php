<div class="content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="card-body bg-light">
        <div class="mb-3 d-flex justify-content-between">
            <div class="d-flex justify-content-center mt-3 gap-2 flex-fill me-auto">
                <div class="col-4">
                    <label for="txtpdf" class="form-label">ເລືອກໄຟລ໌ PDF</label>
                    <input type="file" class="form-control" name="pdfFile" id="txtpdf" accept=".pdf" required>
                </div>
                <div class="mt-auto">
                    <button class="btn btn-primary" id="btnscan" type="button" disabled>
                        <i class="bi bi-file-earmark-arrow-up-fill"></i> ອ່ານ PDF
                    </button>
                </div>
            </div>
            <div class="mt-auto me-3">
                <button class="btn btn-success" id="btnLotLoading">
                    <i class="bi bi-database-fill-check"></i> ລາຍການບັນທຶກ
                </button>
            </div>
        </div>
        <div class="w-100">
            <hr>
        </div>
        <div class="my-3 text-center">
            <span id="lotinfo" class="fs-5"></span>
            <span id="lotcorrect" class="fs-5 ms-3"></span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 me-1">
            <form id="frmpdf" class="d-flex align-items-center p-2 gap-5">
                <div class="d-flex gap-2">
                    <div>
                        <select class="form-select" name="provinceID" id="cbProvince" disabled>
                            <?php
                            include_once ("./database/Province_Options.php");
                            ?>
                        </select>
                    </div>
                    <div>
                        <select class="form-select" name="unitid" id="cbUnit" disabled>
                            <?php
                            include_once ("./database/unit_Option.php");
                            ?>
                        </select>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <button class="btn btn-primary" id="btnshow" type="submit" disabled>
                            <i class="bi bi-search"></i> ສະແດງ
                        </button>
                        <button class="btn btn-info" id="btnReload" disabled>
                            <i class="bi bi-binoculars-fill"></i> ສະແດງທັງໝົດ
                        </button>
                    </div>
                    <div>

                    </div>
                </div>
            </form>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-danger" id="btnpdf" disabled>
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                </button>
                <button class="btn btn-secondary" id="btnSave">
                    <i class="bi bi-floppy2-fill"></i> Save
                </button>
            </div>
        </div>

        <div class="progress" id="progressPDF" role="progressbar" aria-label="Example with label" aria-valuenow="0"
            aria-valuemin="0" aria-valuemax="100" style="height: 10px">
            <div class="progress-bar" style="width: 0%"></div>
        </div>
    </div>
    <table class="table table-bordered mt-2" id="tbshow">
        <thead class="table-light">
            <tr class="text-center">
                <th scope="col">ລຳດັບ</th>
                <th scope="col">ລະຫັດຜູ້ຂາຍ</th>
                <th scope="col">ເລກບິນ</th>
                <th scope="col">ຖືກເລກ 1 ໂຕ</th>
                <th scope="col">ຖືກເລກ 2 ໂຕ</th>
                <th scope="col">ຖືກເລກ 3 ໂຕ</th>
                <th scope="col">ຖືກເລກ 4 ໂຕ</th>
                <th scope="col">ຖືກເລກ 5 ໂຕ</th>
                <th scope="col">ຖືກເລກ 6 ໂຕ</th>
                <th scope="col">ລວມ</th>
                <th scope="col">ໝາຍເຫດ</th>
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

<!-- Modal -->
<div class="modal fade" id="Modalpdfloading" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="w-100 text-center">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">ລາຍການບັນທຶກ PDF ຖືກເລກ</h1>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ol class="list-group list-group-numbered" id="listLotPDF">
                    
                </ol>
            </div>
        </div>
    </div>
</div>

<script>

    $("#btnLotLoading").click(() => {
        $("#Modalpdfloading").modal("show");
        $.get(`./api/LotCorrectPDF.php?api=getbyuserid&id=<?= $_COOKIE['user'] ?>`, (res) => {
            if (res.state) {
                if (res.data.length > 0) {
                    $("#listLotPDF").html("");
                    res.data.forEach(lot => {
                        const lotlist = $(`<li class="list-group-item list-group-item-action">${lot['title']}</li>`);
                        lotlist.click(() => {
                            loadLotSavePDF(lot);
                        });
                        $("#listLotPDF").append(lotlist);
                    });
                } else {
                    $("#listLotPDF").append($(`<li class="list-group-item list-group-item-action">ບໍ່ພົບຂໍ້ມູນ PDF</li>`));
                }
            }
        });
    });

    // ກວດສອບວ່າເລືອກໄຟ
    $("#txtpdf").change(() => {
        $("#btnscan").removeAttr("disabled");
    });
    //ກົດປຸ່ມສະແກນ
    $("#btnscan").click(() => {
        ScanPDF();
    });
    //
    $("#btnReload").click((e) => {
        e.preventDefault();
        const getPDF = localStorage.getItem('pdfdata');
        if (getPDF) {
            const arrdata = JSON.parse(getPDF);
            $("#tableData").html(`
                <tr class="text-center">
                <td colspan='11'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງອ່ານຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
            $("#btnscan").attr('disabled', "disabled");
            createTable(arrdata.data, true, ".......... ບໍ່ພົບຂໍ້ມູນ PDF ..........");
        }
    });

    $("#btnSave").click(() => {
        const getPDF = localStorage.getItem('pdfdata');
        const arrdata = JSON.parse(getPDF);
        const titles = arrdata.title;
        Swal.fire({
            html: `
            <div class="fs-5 fw-bold mb-4">ງວດທີ: ${titles.lotno} ວັນທີ: ${titles.lotdate} ເລກທີອອກ: ${titles.correct}</div>
            <form id="frmLotCorrectPDF" class="p-1">
                <div class="mb-4">
                    <label for="txttitle" class="form-label w-100 text-start">ລາຍລະອຽດ</label>
                    <textarea class="form-control" id="txttitle" rows="3">${getTitleText().replace("-", "/").replace("-", "/")}</textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-floppy2-fill"></i> ບັນທຶກ PDF ຖືກລາງວັນ
                    </button>
                </div>
            </form>`,
            width: 600,
            showConfirmButton: false,
            showCloseButton: true,
            focusCancel: false
        });
        const frmLotCorrectPDF = $("#frmLotCorrectPDF");
        frmLotCorrectPDF.submit((e) => {
            e.preventDefault();
            const savedata = {
                lotno: titles.lotno,
                lotdate: titles.lotdate,
                correct: titles.correct,
                pdfdata: JSON.stringify(arrdata.data),
                title: $("#txttitle").val(),
                userID: <?= $_COOKIE['user'] ?>
            };
            $.post(`./api/LotCorrectPDF.php?api=create`, savedata, (res) => {
                if (res.state) {
                    Swal.fire({
                        title: res.message,
                        icon: res.data
                    });
                } else {
                    Swal.fire({
                        title: res.message,
                        icon: res.data
                    });
                }
            });
        });
    });

    //show data in table with save lot data
    const loadLotSavePDF = (data) => {
        const pdfdata = JSON.parse(data.pdfdata);
        const lotinfo = $("#lotinfo").text(`ງວດທີ່: ${data.lotno} ວັນທີ: ${data.lotdate}`);
        const lotcorrect = $("#lotcorrect").text(`ເລກທີ່ອອກ: ${data.correct}`);
        const createLotdata = { title: getLottitle(), data: pdfdata }
        localStorage.setItem("pdfdata", JSON.stringify(createLotdata));
        $("#Modalpdfloading").modal("hide");
        createTable(pdfdata, true, ".......... ບໍ່ພົບຂໍ້ມູນ PDF ..........");
    }

    const getTitleText = () => {
        const provinceText = $("#cbProvince option:selected").text();
        const unittext = $('#cbUnit option:selected').text();
        const lotinfo = $("#lotinfo").text();
        const lotcorrect = $("#lotcorrect").text();
        // ຍອດຂາຍ ແຂວງ​ໄຊຍະບູລີ ໜ່ວຍ​ ທ.​ເປ​ ວັນທີ່.​ 19/04/2024
        const lotNoText = lotinfo == "" ? "------" : lotinfo.replace("ງວດທີ່: ", "");
        const exportFileName = `ຖືກລາງວັນ ແຂວງ ${provinceText == "---ເລືອກແຂວງ---" ? "ທັງໝົດ" : provinceText} ໜ່ວຍ ${unittext == "---ໜ່ວຍທັງໝົດ---" ? "ທັງໝົດ" : unittext} ງວດທີ ${lotNoText.replace("/", "-").replace("/", "-")} ເລກທີ່ອອກ ${lotcorrect == "" ? "------" : lotcorrect.replace("ເລກທີ່ອອກ: ", "")}`;
        return exportFileName;
    }

    //ກົດປຸ່ມຄົ້ນຫາ
    $("#frmpdf").submit((e) => {
        e.preventDefault();
        const frm = $("#frmpdf").serializeArray();
        const getPDF = localStorage.getItem('pdfdata');
        if (getPDF) {
            const arrdata = JSON.parse(getPDF);
            const unitID = frm[1].value;
            if (unitID != "0") {
                findDataByUnitID(unitID, arrdata.data);
            }
        } else {
            $("#tableData").html(`
                <tr class="text-center">
                <td colspan='11'>
                    <span role="status">ບໍ່ພົບຂໍ້ມູນ PDF ກະລຸນາອ່ານໄຟລ໌ PDF ໃໝ່</span>
                </td>
            </tr>`);
            // $("#btnscan").attr('disabled', "disabled");
        }
    });

    $("#btnpdf").click(() => {
        printPDF();
    });

    const getLottitle = () => {
        const lotinfo = $("#lotinfo").text();
        const lotcorrect = $("#lotcorrect").text();
        const info = lotinfo.split(" ");
        const correct = lotcorrect.split(" ");
        return { lotno: info[1], lotdate: info[3], correct: correct[1] };
    }

    const findDataByUnitID = (unitID, arrdata) => {
        $.get(`./api/sellCodeAPI.php?api=getbyunitid&id=${unitID}`, (res) => {
            const sellcodes = res.data.map(item => item['machineCode']);
            const foundItems = arrdata.filter(item => sellcodes.includes(item[1]));
            $("#tableData").html(`
                <tr class="text-center">
                <td colspan='11'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງອ່ານຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
            createTable(foundItems, false, ".......... ບໍ່ພົບຂໍ້ມູນທີ່ກົງກັບລະຫັດຜຸ້ຂາຍ ..........")
        });
    }

    function ScanPDF() {
        event.preventDefault();
        var fileInput = document.getElementById('txtpdf');
        var file = fileInput.files[0]; // Get the selected file
        if (file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                var pdfData = new Uint8Array(event.target.result);
                extractTextFromPDF(pdfData);
            };
            reader.readAsArrayBuffer(file);
        } else {
            console.error('No file selected.');
        }
    }

    function extractTextFromPDF(pdfData) {
        // Load PDF document
        //ສະແດງໂຫຼດຂໍ້ມູນ
        $("#tableData").html(`
                <tr class="text-center">
                <td colspan='11'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງອ່ານຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
        $("#btnscan").attr('disabled', "disabled");
        ReadPDF(pdfData);
    }

    const ReadPDF = async (pdfData) => {
        try {
            const reading = await pdfjsLib.getDocument({ data: pdfData }).promise;
            let pdfTexts = "";
            for (let pageCount = 1; pageCount <= reading.numPages; pageCount++) {
                const pdf = await reading.getPage(pageCount);
                const page = await pdf.getTextContent();
                const texts = page.items.map(item => {
                    createTitle(item.str);
                    return item.str;
                }).join('|');

                const spaceofpage = pageCount < reading.numPages ? " " : ""
                pdfTexts += texts + spaceofpage;
            }
            createPDFtoArray(pdfTexts);
        } catch (error) {
            console.log(error);
            return "Error";
        }
    }

    function createPDFtoArray(text) {
        const arrs = textToarray(text);
        const groups = groupArray(arrs, 10);
        const data = { title: getLottitle(), data: groups }
        localStorage.setItem("pdfdata", JSON.stringify(data));
        createTable(groups, true, ".......... ບໍ່ພົບຂໍ້ມູນ PDF ..........");
    }

    const createTable = (groups, isShowProgress, emptyText) => {
        $("#pagilist li").remove();
        let lot1 = 0;
        let lot2 = 0;
        let lot3 = 0;
        let lot4 = 0;
        let lot5 = 0;
        let lot6 = 0;
        let amount = 0;
        //ສະແດງຕາຕະລາງ
        let strTable = "";
        if (groups.length <= 0) {
            $("#tableData").html(`
                <tr class="text-center">
                <td colspan='11'>
                    <span role="status">${emptyText}</span>
                </td>
            </tr>`);
        }
        groups.forEach((lot, index) => {
            setTimeout(() => {
                const col = $(`<tr class="text-end"></tr>`);
                col.html(`
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">${lot[1]}</td>
                    <td class="text-center">${lot[2]}</td>
                    <td>${lot[3]}</td>
                    <td>${lot[4]}</td>
                    <td>${lot[5]}</td>
                    <td>${lot[6]}</td>
                    <td>${lot[7]}</td>
                    <td>${lot[8]}</td>
                    <td>${lot[9]}</td>
                    <td></td>`);
                //ລວມເງິນທັງໝົດ
                const sums = calculator(lot);
                lot1 += sums.lot1;
                lot2 += sums.lot2;
                lot3 += sums.lot3;
                lot4 += sums.lot4;
                lot5 += sums.lot5;
                lot6 += sums.lot6;
                amount += sums.amount;
                $("#tableData").append(col);
                col.hide();
                if (groups.length == index + 1) {
                    //ແຖວລວມເງິນທັງໝົດ
                    const col = $(`<tr class="text-end"></tr>`);
                    col.html(`<td colspan="3" class="text-center">ລ່ວມທັງໝົດ</td>
                        <td>${myMoney(lot1)}</td>
                        <td>${myMoney(lot2)}</td>
                        <td>${myMoney(lot3)}</td>
                        <td>${myMoney(lot4)}</td>
                        <td>${myMoney(lot5)}</td>
                        <td>${myMoney(lot6)}</td>
                        <td>${myMoney(amount)}</td>`);
                    //ສະແດງຂໍ້ມູນຕາຕະລາງ
                    $("#tableData").append(col);
                    //ສະແດງປຸ່ມ
                    $("#btnscan").removeAttr("disabled");
                    isShowButton();
                    $("#tableData tr:first").remove();
                    showCurrentPage(500, 1);
                    PaginationEvents(groups, 500);
                }

                if (isShowProgress) {
                    //ກວດສອບສະແດງ Progress
                    showProgressBar(groups.length, index + 1);
                }
            }, index * 2);
        });
    }

    function showCurrentPage(pageSize, currentPage) {
        $('#tableData tr').hide();
        startIndex = (currentPage - 1) * pageSize;
        endIndex = startIndex + pageSize;
        $('#tableData tr').slice(startIndex, endIndex).show();
    }

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

    const createTableToarray = () => {
        const table = $("#tbshow");
        const arr = [];
        table.find("tr").each(function () {
            const cells = [];
            $(this).find("td, th").each(function () {
                cells.push($(this).text());
            });
            arr.push(cells);
        });
        return arr;
    }

    function textToarray(text) {
        const splitText = text.split("|");
        const filteredText = splitText.filter(
            item => item.trim() !== "" && !/^\d{2}\/\d{2}\/\d{4}\t\d{2}:\d{2}:\d{2}$/.test(item)
        );

        const numbersArray = filteredText.filter(item => {
            const value = item.replace(",", "");
            const num = parseFloat(value);
            return !isNaN(num);
        });

        return numbersArray;
    }

    function groupArray(arr, groupSize) {
        const groupedArrays = [];
        let index = 1;
        for (let i = 0; i < arr.length; i += groupSize) {
            const group = arr.slice(i, i + groupSize);
            const num = Number(group[0]);
            //ກວດສອບລຳດັບຕ້ອງລຽງກັນ
            if (index == num) {
                groupedArrays.push(group);
                index++;
            } else {
                //ລົບລາຄາລວ່ມ
                arr.splice(arr[i], 1); //ລົບລະຫັດຜູ້ຂາຍ
                arr.splice(arr[i + 1], 1); //ລົບມູນຄ່າຂາຍໄດ້
                arr.splice(arr[i + 2], 1); //ລົບມູນຄ່າຖືກລາງວັນ
                arr.splice(arr[i + 3], 1); //ລົບຜູ້ຂາຍໜ່ວຍ %
                arr.splice(arr[i + 4], 1); //ລົບຜູ້ຂາຍໜ່ວຍ ມູນຄ່າ
                arr.splice(arr[i + 5], 1); //ລົບຜິດດ່ຽງ
                i -= groupSize;
            }
        }
        return groupedArrays;
    }

    let selectTitleIndex = 0;
    const createTitle = (title) => {
        selectTitleIndex++;
        if (selectTitleIndex >= 3 && selectTitleIndex <= 6) {
            if (selectTitleIndex == 3) {
                //ງວດ ວັນທີ
                const splitText = title.split(" ");
                const text = splitText[0] + ": " + splitText[1] + " " + splitText[2] + ": " + splitText[3];
                $("#lotinfo").text(text);
            } else if (selectTitleIndex == 6) {
                //ເລກອອກ
                const splitText = title.split(" ");
                const text = splitText[0] + ": " + splitText[1];
                $("#lotcorrect").text(text);
            }
        }
    }

    const calculator = (lot) => {
        return {
            lot1: str_number(lot[3]),
            lot2: str_number(lot[4]),
            lot3: str_number(lot[5]),
            lot4: str_number(lot[6]),
            lot5: str_number(lot[7]),
            lot6: str_number(lot[8]),
            amount: str_number(lot[9])
        }
    }

    const isShowButton = () => {
        var rowCount = $('#tableData tr').length;
        $("#btnshow").prop("disabled", rowCount <= 1);
        $("#btnSave").prop("disabled", rowCount <= 1);
        $("#btnReload").prop("disabled", rowCount <= 1);
        $("#btnPrint").prop("disabled", rowCount <= 1);
        $("#btnpdf").prop("disabled", rowCount <= 1);
        $("#cbProvince").prop("disabled", rowCount <= 1);
        $("#cbUnit").prop("disabled", rowCount <= 1);
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
        const val = str.replace(/,/g, '');
        const tonumber = parseFloat(val);
        return Number(tonumber);
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

    function showProgressBar(length, index) {
        //ສະແດງ Progressbar
        $('#progressPDF').show();
        const percent = Math.floor((index / length) * 100);
        // Set the width of the progress bar
        $('#progressPDF .progress-bar').css('width', percent + '%');
        // Update the text inside the progress bar
        // $('#progressPDF .progress-bar').text(percent + '%');
    }

    const printPDF = async () => {
        const tableHtml = $('#tbshow').prop('outerHTML');
        const arrPrint = { "title": getTitleText(), "print": createTableToarray() };
        const { PDFDocument, rgb } = PDFLib;
        const pdfDoc = await PDFDocument.create();
        const fontkit = window.fontkit;
        try {
            const fonturl = './font/boonhome-400.otf';
            const fontBytes = await fetch(fonturl).then((res) => res.arrayBuffer());
            pdfDoc.registerFontkit(fontkit)
            const customFont = await pdfDoc.embedFont(fontBytes);

            const A4Landscap = [841.89, 595.28];
            let page = pdfDoc.addPage(A4Landscap);
            const fontSize = 14;
            const topMargin = 10;
            const leftMargin = 5;
            const linespace = 5;
            const { width, height } = page.getSize();
            const text = arrPrint.title.replace("-", "/").replace("-", "/");
            const textsize = getTextSize(customFont, text, fontSize);
            const startY = height - textsize.height;
            const endX = width - textsize.width;

            page.drawText(text, {
                x: endX / 2,
                y: startY - topMargin,
                size: fontSize,
                font: customFont,
            });

            const arrheader = ["ລຳດັບ", "ລະຫັດຜູ້ຂາຍ", "ເລກບິນ", "ຖືກເລກ 1 ໂຕ", "ຖືກເລກ 2 ໂຕ", "ຖືກເລກ 3 ໂຕ", "ຖືກເລກ 4 ໂຕ", "ຖືກເລກ 5 ໂຕ", "ຖືກເລກ 6 ໂຕ", "ລວມ", "ໝາຍເຫດ"];
            let rowspace = -50;
            const arrX = [5, 35, 99.33333333333334, 266.5, 336.25, 407.1666666666667, 478.25, 549.25, 620.4166666666666, 691.6666666666666, 776.75];
            const arrTextwidth = [22, 46.33333333333334, 26.16666666666667, 45.75, 46.91666666666667, 47.08333333333333, 47, 47.16666666666667, 47.25, 17.083333333333336, 35.666666666666664];
            const arrBorderW = [0, 10, 133, 16, 16, 16, 16, 16, 16, 60, 16];
            arrheader.forEach((text, index) => {
                const Row = createRow(page, rgb, arrX[index], -50, text, arrTextwidth[index], 10, customFont, arrBorderW[index], "center");
            });

            let rowheight = -50 - 21.166666666666664;
            arrPrint.print.shift();
            const sumRow = arrPrint.print.pop();
            arrPrint.print.forEach((lots, index) => {
                if (rowheight <= -580) {
                    page = pdfDoc.addPage(A4Landscap);
                    rowheight = -25;
                }
                createRow(page, rgb, arrX[0], rowheight, lots[0], arrTextwidth[0], 10, customFont, arrBorderW[0], "center");
                createRow(page, rgb, arrX[1], rowheight, lots[1], arrTextwidth[1], 10, customFont, arrBorderW[1], "center");
                createRow(page, rgb, arrX[2], rowheight, lots[2], arrTextwidth[2], 10, customFont, arrBorderW[2], "center");
                createRow(page, rgb, arrX[3], rowheight, lots[3], arrTextwidth[3], 10, customFont, arrBorderW[3], "end");
                createRow(page, rgb, arrX[4], rowheight, lots[4], arrTextwidth[4], 10, customFont, arrBorderW[4], "end");
                createRow(page, rgb, arrX[5], rowheight, lots[5], arrTextwidth[5], 10, customFont, arrBorderW[5], "end");
                createRow(page, rgb, arrX[6], rowheight, lots[6], arrTextwidth[6], 10, customFont, arrBorderW[6], "end");
                createRow(page, rgb, arrX[7], rowheight, lots[7], arrTextwidth[7], 10, customFont, arrBorderW[7], "end");
                createRow(page, rgb, arrX[8], rowheight, lots[8], arrTextwidth[8], 10, customFont, arrBorderW[8], "end");
                createRow(page, rgb, arrX[9], rowheight, lots[9], arrTextwidth[9], 10, customFont, arrBorderW[9], "end");
                createRow(page, rgb, arrX[10], rowheight, lots[10], arrTextwidth[10], 10, customFont, arrBorderW[10], "");
                rowheight += - 21.166666666666664;
            });

            //sumRow
            const borderSumwidth = arrBorderW[0] + arrBorderW[1] + arrBorderW[2] + 16;
            const textsumWidth = arrTextwidth[0] + arrTextwidth[1] + arrTextwidth[2];

            createRow(page, rgb, arrX[0], rowheight, sumRow[0], textsumWidth, 10, customFont, borderSumwidth, "center");
            createRow(page, rgb, arrX[3], rowheight, sumRow[1], arrTextwidth[3], 10, customFont, arrBorderW[3], "end");
            createRow(page, rgb, arrX[4], rowheight, sumRow[2], arrTextwidth[4], 10, customFont, arrBorderW[4], "end");
            createRow(page, rgb, arrX[5], rowheight, sumRow[3], arrTextwidth[5], 10, customFont, arrBorderW[5], "end");
            createRow(page, rgb, arrX[6], rowheight, sumRow[4], arrTextwidth[6], 10, customFont, arrBorderW[6], "end");
            createRow(page, rgb, arrX[7], rowheight, sumRow[5], arrTextwidth[7], 10, customFont, arrBorderW[7], "end");
            createRow(page, rgb, arrX[8], rowheight, sumRow[6], arrTextwidth[8], 10, customFont, arrBorderW[8], "end");
            createRow(page, rgb, arrX[9], rowheight, sumRow[7], arrTextwidth[9], 10, customFont, arrBorderW[9], "end");
            createRow(page, rgb, arrX[10], rowheight, "", arrTextwidth[10], 10, customFont, arrBorderW[10], "end");

            // Serialize the PDF document to bytes
            const pdfBytes = await pdfDoc.save();

            // Download the PDF (assuming you have this logic)
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = url;
            link.download = `${arrPrint.title}.pdf`;
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

    const createRow = (page, rgb, x = 0, y = 0, text, textWidth, fontsize, customFont, lineW = 0, textAlign = "start") => {
        const { width, height } = page.getSize();
        const textData = getTextSize(customFont, text, fontsize);
        const pading = 8;
        const lineY = y + height - textData.height;
        const lineWidth = textWidth + pading + lineW;
        const lineHeight = textData.height + pading * 2;
        let positionX = x;
        switch (textAlign) {
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
        const positionY = y + height - textData.height + pading / 1.1;
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

</script>