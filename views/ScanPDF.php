<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="d-flex bg-secondary-subtle flex-column align-items-center p-2 mb-1">
        <form class="d-flex justify-content-center align-items-center gap-2 col-8 mt-2" id="frmPDF">
            <div>
                <label for="txtpdf" class="form-label">ເລືອກໄຟລ໌ PDF</label>
            </div>
            <div class="col">
                <input type="file" class="form-control" name="pdfFile" id="txtpdf" accept=".pdf" required>
            </div>
            <div>
                <button class="btn btn-primary" type="submit"><i class='bx bxs-file-pdf'></i> ອ່ານຂໍ້ມູນ PDF</button>
            </div>
        </form>
        <hr>
        <div class="d-flex justify-content-between w-100">
            <button class="btn btn-success" id="btnScanUnit">
                <i class='bx bxs-user-rectangle'></i>
                ສະແດງຕາມລະຫັດໜ່ວຍ
            </button>
            <div class="ms-auto d-flex gap-2">
                <input type="search" class="form-control" placeholder="ຄົ້ນຫາ" id="txt-search">
                <button class="btn btn-primary">ສະແດງ</button>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped">
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
    $("#btnScanUnit").click((e) => {
        $.get(`./api/sellCodeAPI.php?api=getall`, (res) => {
            const getSellcode = res.data;
            const apiCode = [];
            const pdfCode = [];
            getSellcode.forEach(unit => {
                apiCode.push(unit['machineCode']);
            });
            sellCodes.forEach(unit => {
                unit.forEach(item => {
                    pdfCode.push(item[1]);
                });
            });
            // console.log(apiCode);
            // console.log(pdfCode);
            const sameCode = compareArrays(pdfCode, apiCode);
            $("#tableData").html("");
            sellCodes.forEach(datas => {
                datas.forEach(data => {
                    sameCode.forEach(same => {
                        if (data[1] == same) {
                            console.log(data);
                            createSameRow(data);
                        }
                    });
                });
            });
        });
    });

    const compareArrays = (arr1, arr2) => {
        const sameElements = [];
        for (let i = 0; i < arr1.length; i++) {
            if (arr2.includes(arr1[i])) {
                sameElements.push(arr1[i]);
            }
        }
        return sameElements;
    }

    // Function to extract text from PDF
    $(document).ready(function () {
        $("#btnScanUnit").hide();
        const formFile = $("#frmPDF");
        formFile.submit((event) => {
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
        });
    });


    function extractTextFromPDF(pdfData) {
        // Load PDF document
        pdfjsLib.getDocument({ data: pdfData }).promise.then(function (pdf) {
            // Fetch text content from each page

            var pagesPromises = [];
            for (var pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                pagesPromises.push(pdf.getPage(pageNum).then(function (page) {
                    return page.getTextContent().then(function (textContent) {
                        return textContent.items.map(function (item) {
                            return item.str;
                        }).join('|');
                    });
                }));
            }

            // Once all pages are processed, display or process the extracted text
            Promise.all(pagesPromises).then(function (pagesText) {
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
        const removeLast = groups.slice(0, groups.length - 1);
        const col = document.getElementById("tableData");
        sellCodes.push(removeLast);
        let strTable = "";
        removeLast.forEach(lot => {
            strTable += `
                <tr class="text-end">
                    <td class="text-center">${lot[0]}</td>
                    <td class="text-center">${lot[1]}</td>
                    <td>${lot[2]}</td>
                    <td>${lot[3]}</td>
                    <td class="col-1">${lot[4]}</td>
                    <td class="col-1">${lot[5]}</td>
                    <td>${lot[6]}</td>
                </tr>`;
        });
        col.innerHTML = strTable;
        showButtonUnit();
    }

    function createSameRow(lot) {
        const row = $("<tr class='text-end'></tr>");
        row.html(`
                    <td class="text-center">${lot[0]}</td>
                    <td class="text-center">${lot[1]}</td>
                    <td>${lot[2]}</td>
                    <td>${lot[3]}</td>
                    <td class="col-1">${lot[4]}</td>
                    <td class="col-1">${lot[5]}</td>
                    <td>${lot[6]}</td>
                    `);
        $("#tableData").append(row);
        showButtonUnit();
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
        for (let i = 0; i < arr.length; i += groupSize) {
            const group = arr.slice(i, i + groupSize);
            groupedArrays.push(group);
        }
        return groupedArrays;
    }

    function removeNonNumbers(data) {
        return data.filter(element => Number.isFinite(Number(element)));
    }

    function showButtonUnit() {
        var rowCount = $('#tableData tr').length;
        if (rowCount > 0) {
            $("#btnScanUnit").show();
        } else {
            $("#btnScanUnit").hide();
        }
    }

</script>