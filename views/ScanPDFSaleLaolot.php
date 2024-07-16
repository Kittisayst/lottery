<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <script>
        $(function () {
            $("#datepicker").datepicker({
                dateFormat: "dd/mm/yy"
            });
        });
    </script>
    <div class="card mb-1">
        <div class="card-body bg-light">
            <form id="frmSave" class="d-flex gap-5">
                <div class="col">
                    <div class="mb-3 d-flex gap-2">
                        <div class="col">
                            <label for="txtno" class="form-label">ເລກທີ</label>
                            <input type="text" id="txtno" name="lotteryNo" class="form-control" placeholder="ເລກທີ"
                                required>
                        </div>
                        <div class="col">
                            <label for="cblotID" class="form-label">ເລກທີໃນລະບົບ</label>
                            <select class="form-select" name="lotID" id="cblotID" required>
                                <?php
                                require_once "./database/LotteryOption.php";
                                ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="datepicker" class="form-label">ວັນທີ</label>
                        <input type="text" id="datepicker" name="lotDate" class="form-control" placeholder="ວັນທີ"
                            autocomplete="off" required>
                    </div>
                </div>
                <div class="col d-flex flex-column">
                    <div class="mb-3 d-flex gap-2">
                        <div class="col">
                            <label for="txtpdf" class="form-label">ເລືອກໄຟລ໌ PDF</label>
                            <input type="file" class="form-control" name="pdfFile" id="txtpdf" accept=".xlsx" required>
                        </div>
                        <div class="mt-auto">
                            <button class="btn btn-primary" id="btnscan" type="button" disabled>
                                <i class="bi bi-file-earmark-arrow-up-fill"></i> ອ່ານ PDF
                            </button>
                        </div>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <button class="btn btn-success btn-lg w-100" type="submit" id="btnSave" disabled>
                            <i class="bi bi-floppy2-fill"></i> ບັນທຶກ
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="progress" id="progressPDF" role="progressbar" aria-label="Example with label" aria-valuenow="0"
            aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: 0%">0%</div>
        </div>
    </div>
    <div class="d-flex justify-content-end p-3">
        <button class="btn btn-warning" id="btnmissingcode"><i class="bi bi-exclamation-triangle"></i>
            ລະຫັດຄ້າງ</button>
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

<!-- Modal Missingcode-->
<div class="modal fade" id="Modalmissingcode" tabindex="-1" aria-labelledby="missingcodeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 w-100 text-center" id="missingcodeLabel">ລະຫັດຄ້າງ</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="txtmissingcode" class="form-control"></textarea>
            </div>
        </div>
    </div>
</div>

<script>
    const arrDataPDF = [];

    $(document).ready(function () {
        // ກວດສອບວ່າເລືອກໄຟ
        $("#txtpdf").change(() => {
            $("#btnscan").removeAttr("disabled");
        });
        //ກົດປຸ່ມສະແກນ
        $("#btnscan").click(() => {
            ScanPDF();
        });
        //
        $("#btnmissingcode").hide();
        //ກົດປຸ່ມບັນທຶກ
        $("#frmSave").submit((e) => {
            e.preventDefault();
            const frm = $("#frmSave").serializeArray();
            if (isDateFormat(frm[2].value) && frm[2].value != "01/01/1970") {
                $("#btnSave").attr("disabled", "disabled");
                if (arrDataPDF.length > 0) {
                    const fileInput = document.getElementById('txtpdf');
                    const file = fileInput.files[0];
                    const lotteryNo = frm[0].value;
                    const lotID = frm[1].value;
                    const lotdate = frm[2].value;
                    const fileName = file.name;
                    const fileSize = file.size / 1024;
                    const pdfData = [];
                    const UserID = <?= $_COOKIE['user'] ?>;
                    arrDataPDF.forEach((lot, index) => {
                        //create Json Data
                        pdfData.push(
                            {
                                "machineCode": lot.code,
                                "Sales": lot.sales,
                                "Award": 0,
                                "Percentage": lot.Percentage,
                                "Price": lot.colPercentage,
                                "Amount": lot.amount
                            });

                        //save data when finished loop
                        if (index == arrDataPDF.length - 1) {
                            const createData = {
                                "lotteryNo": lotteryNo,
                                "lotteryID": lotID,
                                "lotDate": lotdate,
                                "FileName": fileName,
                                "fileSize": fileSize.toFixed(2),
                                "pdfData": JSON.stringify(pdfData),
                                "UserID": UserID
                            };
                            //send to api
                            // console.log(createData);
                            save(createData);
                        }
                        //show progress bar
                        showProgressBar(arrDataPDF.length, index + 1);
                    });
                    console.log(pdfData);
                }
            } else {
                Swal.fire({
                    title: "ຂໍ້ມູນບໍ່ຖືກຕ້ອງ?",
                    text: "ກະລຸນາກວດສອບວັນທີ່ຂອງທ່ານ!",
                    icon: "warning"
                });
            }
        });
        // $('#progressPDF').hide();
    });

    const save = (createData) => {
        $.post(`./api/SalePDFAPILaolot.php?api=create`, createData, (res) => {
            if (res.state) {
                Swal.fire({
                    title: res.message,
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonText: "ຄົ້ນຫາຕາມໜ່ວຍ",
                    cancelButtonText: "ກັບຄືນ"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `?page=scanpayment&id=${res.data}`;
                    } else {
                        location.href = "?page=salepdf";
                    }
                });
            } else {
                Swal.fire({
                    title: res.message,
                    icon: res.data
                }).finally(() => {
                    $("#btnSave").removeAttr("disabled");
                });
            }
        });
    }

    function ScanPDF() {
        event.preventDefault();
        var fileInput = document.getElementById('txtpdf');
        var file = fileInput.files[0]; // Get the selected file
        if (file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, { type: 'array' });

                // Process the workbook here
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                const json = XLSX.utils.sheet_to_json(worksheet);
                const lotNodata = json.shift();//ລົບແຖວທຳອິດຫົວຂໍ້
                showLotDate(lotNodata); //ສະແດງວັນທີ
                json.pop();//ລົບແຖວສຸດທ້າຍ
                const convertedArray = json.map(originalObject => {
                    return {
                        code: originalObject["__EMPTY"],
                        sale: originalObject["__EMPTY_1"]
                    };
                });
                extractTextFromPDF(convertedArray);
            };
            reader.readAsArrayBuffer(file);
        } else {
            console.error('No file selected.');
        }
    }



    function extractTextFromPDF(excelData) {
        // Load PDF document
        //ສະແດງໂຫຼດຂໍ້ມູນ
        $("#tableData").html(`
                <tr class="text-center">
                <td colspan='7'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງອ່ານຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
        $("#btnscan").attr('disabled', "disabled");
        leadPDF(excelData);
    }
    const leadPDF = async (excelData) => {
        const resUnitcode = await fetch(`./api/sellCodeLotlink.php?api=getall`);
        const datas = await resUnitcode.json();
        const dbCode = datas.data.map(item => Number(item.machineCode));
        const missingCode = excelData.filter(item => !dbCode.includes(item.code));
        createMissingValue(missingCode);
        const unitCodeData = datas.data.map(item => ({
            Percentage: Number(item.Percentage),
            UnitID: item.UnitID,
            code: Number(item.machineCode),
            machineID: item.machineID,
            unitID: item.unitID,
            unitName: item.unitName,
            withdrawn: Number(item.withdrawn) == 1
        }));

        const createScandata = unitCodeData.map(unit => {
            const machine = excelData.find(machine => machine.code == unit.code);
            if (machine) {
                const money = (machine.sale * unit.Percentage) / 100;
                const amount = machine.sale - money;
                return { ...unit, sales: machine.sale, colPercentage: money, amount: amount }
            }
            return { ...unit, sales: 0, colPercentage: 0, amount: 0 }
        });

        createTable(createScandata);
    }

    function showLotDate(lotdate) {
        const value = extractDate(lotdate);
        $("#datepicker").val(value);
    }

    function extractDate(obj) {
        const dateKey = Object.keys(obj).find(key => key.includes("ວັນທີ"));
        const dateMatch = dateKey.match(/\d{2}\/\d{2}\/\d{4}/);
        return dateMatch ? dateMatch[0] : null;
    }

    function createTable(tableData) {
        $("#tableData").html("");
        //ສະແດງໂຫຼດຂໍ້ມູນ        
        let strTable = "";
        let sales = 0;
        let award = 0;
        let calpercent = 0;
        let amount = 0;
        //ສະແດງຕາຕະລາງ
        let paginationCount = tableData.length;
        tableData.forEach((lot, index) => {
            setTimeout(() => {
                if (lot.sales > 0) {
                    arrDataPDF.push(lot);
                    const col = $(`<tr class="text-end"></tr>`);
                    col.html(`<td class="text-center">${index + 1}</td>
                    <td class="text-center">${lot.code}</td>
                    <td>${myMoney(lot.sales)}</td>
                    <td>${lot.unitName}</td>
                    <td class="col-1 text-center">${lot.Percentage}</td>
                    <td class="col-1">${myMoney(lot.colPercentage)}</td>
                    <td>${myMoney(lot.amount)}</td>`);
                    $('#tableData').append(col);
                    col.hide();
                    // ລວມເງິນທັງໝົດ
                    sales += lot.sales;
                    calpercent += lot.colPercentage;
                    amount += lot.amount;
                } else {
                    paginationCount = paginationCount - 1;
                }
                showProgressBar(tableData.length, index + 1);
                if (tableData.length == index + 1) {
                    //ແຖວລວມເງິນທັງໝົດ
                    $("#tableData").append(`<tr class="text-end"><td colspan="2" class="text-center">ລ່ວມທັງໝົດ</td>
                        <td>${myMoney(sales)}</td>
                        <td>-</td>
                        <td class="text-center">-</td>
                        <td>${myMoney(calpercent)}</td>
                        <td>${myMoney(amount)}</td>
                    </tr>`);
                    //ສະແດງປຸ່ມ
                    $("#btnscan").removeAttr("disabled");
                    isShowButton();
                    showCurrentPage(500, 1);
                    console.log(tableData.length);
                    console.log(paginationCount);
                    PaginationEvents(paginationCount, 500);
                }
            }, index * 2);
        });
    }

    function createMissingValue(missingValues) {
        if (missingValues.length > 0) {
            $("#btnmissingcode").show();
            $("#txtmissingcode").attr("rows", missingValues.length);
            let str = "";
            missingValues.forEach(item => {
                str += `${item.code}\t ${item.sale} \n`;
            });
            $("#txtmissingcode").val(str);
            $("#btnmissingcode").click(() => {
                $("#Modalmissingcode").modal('show');
            });
        }
    }

    function showCurrentPage(pageSize, currentPage) {
        $('#tableData tr').hide();
        startIndex = (currentPage - 1) * pageSize;
        endIndex = startIndex + pageSize;
        $('#tableData tr').slice(startIndex, endIndex).show();
    }

    function PaginationEvents(tableData, buttons) {
        const countButton = tableData / buttons;
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

    const isShowButton = () => {
        var rowCount = $('#tableData tr').length;
        $("#btnshow").prop("disabled", rowCount <= 1);
        $("#btnSave").prop("disabled", rowCount <= 1);
    }

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
        $('#progressPDF .progress-bar').text(percent + '%');
    }

    function setProgressBarDefault() {
        $('#progressPDF .progress-bar').css('width', 0 + '%');
        // Update the text inside the progress bar
        $('#progressPDF .progress-bar').text(0 + '%');
    }

    function isDateFormat(dateString, format) {
        return moment(dateString, "DD/MM/YYYY", true).isValid();
    }

    $("#alert_title").text($("#alert_title").text() + " (ລາວລອດ)");
</script>