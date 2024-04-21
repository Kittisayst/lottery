<div class="content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="card-body bg-light">
        <div class="mb-3 d-flex justify-content-center gap-2">
            <div class="col-5 d-flex mt-3 gap-2">
                <div class="col">
                    <label for="txtpdf" class="form-label">ເລືອກໄຟລ໌ PDF</label>
                    <input type="file" class="form-control" name="pdfFile" id="txtpdf" accept=".pdf" required>
                </div>
                <div class="mt-auto">
                    <button class="btn btn-primary" id="btnscan" type="button" disabled>
                        <i class="bi bi-file-earmark-arrow-up-fill"></i> ອ່ານ PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="w-100">
            <hr>
        </div>
        <div class="my-3 text-center">
            <span id="lotinfo" class="fs-5">ຂໍ້ມູນ PDF</span>
            <span id="lotcorrect" class="fs-5 ms-3"></span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 me-1">
            <form id="frmpdf" class="d-flex align-items-center p-2 gap-5">
                <div class="d-flex gap-2">
                    <div>
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
                    <div class="d-flex align-items-center gap-4">
                        <button class="btn btn-primary" id="btnshow" type="submit">
                            <i class="bi bi-search"></i> ສະແດງ
                        </button>
                        <button class="btn btn-info" id="btnReload">
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
                <button class="btn btn-success" id="btnexcel" disabled>
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel
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
</div>

<script>
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
            $("#tableData").html("");
            createTable(arrdata, true, ".......... ບໍ່ພົບຂໍ້ມູນ PDF ..........");
        }
    });

    //Export Excel
    $("#btnexcel").on("click", () => {
        const provinceText = $("#cbProvince option:selected").text();
        const unittext = $('#cbUnit option:selected').text();
        const lotcorrect = $("#lotcorrect").text();
        // ຍອດຂາຍ ແຂວງ​ໄຊຍະບູລີ ໜ່ວຍ​ ທ.​ເປ​ ວັນທີ່.​ 19/04/2024
        const commentText = `ຖືກລາງວັນ ແຂວງ ${provinceText} ໜ່ວຍ ${unittext} ເລກອອກ ${lotcorrect}`;
        const table = document.getElementById("tbshow");
        const workbook = XLSX.utils.table_to_book(table, {
            sheet: `ເລກອອກ ${lotcorrect}`
        });

        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        const range = XLSX.utils.decode_range(sheet['!ref']);
        for (let i = range.s.r; i <= range.e.r; i++) {
            const cellAddress = XLSX.utils.encode_cell({ r: i, c: 2 });
            sheet[cellAddress].t='s';
            console.log(sheet[cellAddress]);
            // console.log(cellAddress);
            // if (sheet[cellAddress]) {
            //     sheet[cellAddress].t = 's'; // Set cell type to string (text)
            // }
        }

        // Access the first sheet in the workbook


        // Set the format for column 2 to text
        // const range = XLSX.utils.decode_range(sheet['!ref']);
        // for (let i = range.s.r; i <= range.e.r; i++) {
        //     const cellAddress = XLSX.utils.encode_cell({ r: i, c: 3 });
        //     console.log(cellAddress);
        //     if (sheet[cellAddress]) {
        //         sheet[cellAddress].t = 's'; // Set cell type to string (text)
        //     }
        // }
        // console.log(workbook);

        // XLSX.writeFile(workbook, `${commentText}.xlsx`);
    });

    //ກົດປຸ່ມຄົ້ນຫາ
    $("#frmpdf").submit((e) => {
        e.preventDefault();
        const frm = $("#frmpdf").serializeArray();

        const getPDF = localStorage.getItem('pdfdata');
        if (getPDF) {
            const arrdata = JSON.parse(getPDF);
            const unitID = frm[1].value;
            if (unitID != "0") {
                findDataByUnitID(unitID, arrdata);
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

    const findDataByUnitID = (unitID, arrdata) => {
        $.get(`./api/sellCodeAPI.php?api=getbyunitid&id=${unitID}`, (res) => {
            const sellcodes = res.data.map(item => item['machineCode']);
            const foundItems = arrdata.filter(item => sellcodes.includes(item[1]));
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

    const checkPDF = async (pdfData) => {
        try {
            const reading = await pdfjsLib.getDocument({ data: pdfData }).promise;
            const pdf = await reading.getPage(1);
            const page = await pdf.getTextContent();
            const texts = page.items;
            console.log(texts[2].str);
            console.log(texts[5].str);
        } catch (error) {
            console.log(error);
        }
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
        localStorage.setItem("pdfdata", JSON.stringify(groups));
        createTable(groups, true, ".......... ບໍ່ພົບຂໍ້ມູນ PDF ..........");
    }

    const createTable = (groups, isShowProgress, emptyText) => {
        $("#tableData").html("");
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
                    <td class="text-center">${lot[0]}</td>
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
                }

                if (isShowProgress) {
                    //ກວດສອບສະແດງ Progress
                    showProgressBar(groups.length, index + 1);
                }
            }, index * 2);
        });
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
        $("#btnexcel").prop("disabled", rowCount <= 1);
        $("#btnpdf").prop("disabled", rowCount <= 1);
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
</script>