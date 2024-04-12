<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="d-flex bg-secondary-subtle flex-column align-items-center p-2 mb-1">
        <div class="d-flex w-100 gap-5">
            <span class="fs-5">ງວດທີ: <span id="lot">.....</span></span>
            <span class="fs-5">ວັນທີ: <span id="lotdate">.....</span></span>
            <span class="fs-5">ເລກທີ່ອອກ: <span class="award">.....</span></span>
        </div>
        <div class="w-100  mb-3">
            <hr>
        </div>
        <form class="d-flex justify-content-center align-items-center gap-2 col-8 mt-2" id="frmPDF">
            <div>
                <label for="txtpdf" class="form-label">ເລືອກໄຟລ໌ PDF</label>
            </div>
            <div class="col">
                <input type="file" class="form-control" name="pdfFile" id="txtpdf" accept=".pdf" required>
            </div>
            <div>
                <button class="btn btn-primary" id="btnscan" type="submit"><i class='bx bxs-file-pdf'></i> ອ່ານຂໍ້ມູນ
                    PDF</button>
            </div>
        </form>
        <hr>
        <div class="d-flex justify-content-between w-100">
            <form class="d-flex align-items-center gap-2" id="frmshowUint">
                <div class="d-flex align-items-center gap-2">
                    <label for="cbProvince" class="form-label">ແຂວງ</label>
                    <select class="form-select" name="provinceID" id="cbProvince">
                        <?php
                        include_once ("./database/Province_Options.php");
                        ?>
                    </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                    <select class="form-select" name="unitid" id="cbUnit">
                        <?php
                        include_once ("./database/unit_Option.php");
                        ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" disabled id="btnshow">ສະແດງ</button>
                </div>
            </form>
            <div>
                <button class="btn btn-danger" id="btnsavepdf" disabled><i class="bi bi-file-earmark-pdf-fill"></i> Save
                    PDF</button>
                <button class="btn btn-success" id="btnsaveExcel" disabled><i
                        class="bi bi-file-earmark-spreadsheet-fill"></i> Save Excel</button>
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
</div>
<script>
    const sellCodes = [];
    const titles = [];
    const frmshowUint = $("#frmshowUint");

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

    frmshowUint.submit((e) => {
        e.preventDefault();
        const formData = frmshowUint.serializeArray();
        location.href = `?page=scanpayment&pid=${formData[0].value}&unitID=${formData[1].value}`;
    });


    // $("#btnScanUnit").click((e) => {
    //     $.get(`./api/sellCodeAPI.php?api=getall`, (res) => {
    //         const getSellcode = res.data;
    //         const apiCode = [];
    //         const pdfCode = [];
    //         getSellcode.forEach(unit => {
    //             apiCode.push(unit['machineCode']);
    //         });
    //         sellCodes.forEach(unit => {
    //             unit.forEach(item => {
    //                 pdfCode.push(item[1]);
    //             });
    //         });

    //         const sameCode = compareArrays(pdfCode, apiCode);
    //         $("#tableData").html("");
    //         sellCodes.forEach(datas => {
    //             datas.forEach(data => {
    //                 sameCode.forEach(same => {
    //                     if (data[1] == same) {
    //                         console.log(data);
    //                         createSameRow(data);
    //                     }
    //                 });
    //             });
    //         });
    //     });
    // });

    // const compareArrays = (arr1, arr2) => {
    //     const sameElements = [];
    //     for (let i = 0; i < arr1.length; i++) {
    //         if (arr2.includes(arr1[i])) {
    //             sameElements.push(arr1[i]);
    //         }
    //     }
    //     return sameElements;
    // }

    // Function to extract text from PDF
    $(document).ready(function () {
        // ລົບຂໍ້ມູນການໂຫຼດໄຟ pdf
        localStorage.clear();
        const formFile = $("#frmPDF");
        formFile.submit((event) => {
            event.preventDefault();
            //ສະແດງໂຫຼດຂໍ້ມູນ
            $("#tableData").html(`
                <tr class="text-center">
                <td colspan='7'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງອ່ານຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
            $("#btnscan").attr('disabled', "disabled");

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
        });
    });


    function extractTextFromPDF(pdfData) {
        // Load PDF document
        pdfjsLib.getDocument({ data: pdfData }).promise.then(function (pdf) {
            var pagesPromises = [];
            for (var pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                pagesPromises.push(pdf.getPage(pageNum).then(function (page) {
                    return page.getTextContent().then(function (textContent) {
                        return textContent.items.map(function (item) {
                            //ສ້າງຫົວຂໍ້ງວດທີ່ ວັນທີ່
                            // console.log(item.str);
                            createTitle(item.str);
                            return item.str;
                        }).join('|');
                    });
                }));
            }

            // Once all pages are processed, display or process the extracted text
            Promise.all(pagesPromises).then(function (pagesText) {
                //pagesText  ຂໍ້ມູນໃນ1ໜ້າ ຄ່າເປັນ array 
                var extractedText = pagesText.join(' ');
                parseTableFromText(extractedText);
            }).catch(function (error) {
                console.error('Error extracting text:', error);
            });
        }).catch(function (error) {
            console.error('Error loading PDF:', error);
        });
    }

    function parseTableFromText(text) {
        const arrs = textToarray(text);
        const groups = groupArray(arrs, 7);
        const col = document.getElementById("tableData");
        $("#tableData").html("");
        let strTable = "";
        let sales = 0;
        let award = 0;
        let calpercent = 0;
        let amount = 0;
        groups.forEach(lot => {
            strTable += `
                <tr class="text-end">
                    <td class="text-center">${lot[0]}</td>
                    <td class="text-center">${lot[1]}</td>
                    <td>${lot[2]}</td>
                    <td>${lot[3]}</td>
                    <td class="col-1 text-center">${lot[4]}</td>
                    <td class="col-1">${lot[5]}</td>
                    <td>${lot[6]}</td>
                </tr>`;
            //ລວມເງິນທັງໝົດ
            const sums = calculator(lot);
            sales += sums.sales;
            award += sums.award;
            calpercent += sums.calpercent;
            amount += sums.amount;
        });
        //ແຖວລວມເງິນທັງໝົດ
        strTable += `
        <tr class="text-end">
            <td colspan="3">${myMoney(sales)}</td>
            <td>${myMoney(award)}</td>
            <td class="text-center">-</td>
            <td>${myMoney(calpercent)}</td>
            <td>${myMoney(amount)}</td>
        </tr>`;
        //ສະແດງຂໍ້ມູນຕາຕະລາງ
        $("#tableData").html(strTable);
        //ສະແດງປຸ່ມ
        $("#btnscan").removeAttr("disabled");
        isShowButton();
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

        numbersArray.shift();
        numbersArray.shift();
        numbersArray.shift();
        numbersArray.shift();
        numbersArray.shift();
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
        // localStorage.setItem("pdfdata",JSON.stringify(groupedArrays));
        return groupedArrays;
    }

    function removeNonNumbers(data) {
        return data.filter(element => Number.isFinite(Number(element)));
    }

    let selectTitleIndex = 0;
    const createTitle = (title) => {
        selectTitleIndex++;
        if (selectTitleIndex == 5) {
            const splitText = title.split(" ");
            $("#lot").text(splitText[1]);
            $("#lotdate").text(splitText[3]);
            $("#award").text("..................");
        }
    }

    const calculator = (lot) => {
        return {
            sales: str_number(lot[2]),
            award: str_number(lot[3]),
            calpercent: str_number(lot[5]),
            amount: str_number(lot[6])
        }
    }

    const isShowButton = () => {
        var rowCount = $('#tableData tr').length;
        $("#btnsavepdf").prop("disabled", rowCount <= 1);
        $("#btnsaveExcel").prop("disabled", rowCount <= 1);
        $("#btnshow").prop("disabled", rowCount <= 1);
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

    $("#btnsavepdf").on("click", () => {
        const Month = new Date().getMonth() + 1;
        var element = document.getElementsByTagName('table')[0];
        var options = {
            jsPDF: { // jsPDF options
                orientation: 'portrait', // A4 is portrait by default
                unit: 'mm', // Set unit to millimeters
                format: [210, 297] // A4 size in millimeters
            }
        };
        html2pdf().from(element).set(options).toPdf().save(`ຍອດຂາຍ ແລະ ຖືກລາງວັນ ${Month} ${jdateTimeNow()}.pdf`);
    });

    const str_number = (str) => {
        const val = str.replace(",", "");
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

</script>