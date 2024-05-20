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
                            <input type="file" class="form-control" name="pdfFile" id="txtpdf" accept=".pdf" required>
                        </div>
                        <div class="mt-auto">
                            <button class="btn btn-primary" id="btnscan" type="button" disabled>
                                <i class="bi bi-file-earmark-arrow-up-fill"></i> ອ່ານ PDF
                            </button>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <button class="btn btn-success btn-lg w-100" type="submit" id="btnSave" disabled><i
                                class="bi bi-floppy2-fill"></i> ບັນທຶກ</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="progress" id="progressPDF" role="progressbar" aria-label="Example with label" aria-valuenow="0"
            aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: 0%">0%</div>
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
                                "machineCode": lot[1],
                                "Sales": lot[2],
                                "Award": lot[3],
                                "Percentage": lot[4],
                                "Price": lot[5],
                                "Amount": lot[6]
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
        $.post(`./api/SalePDFAPI.php?api=create`, createData, (res) => {
            if (res.state) {
                Swal.fire({
                    title: res.message,
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonText: "ຄົ້ນຫາຕາມໜ່ວຍ",
                    cancelButtonText: "ກັບຄືນ"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `?page=scanpayment&id=${res.data}&limit=100&pagination=1`;
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
                <td colspan='7'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງອ່ານຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
        $("#btnscan").attr('disabled', "disabled");
        leadPDF(pdfData);
    }

    const leadPDF = async (pdfData) => {
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
            parseTableFromText(pdfTexts);
        } catch (error) {
            console.log(error);
            return "Error";
        }
    }

    function parseTableFromText(text) {
        const arrs = textToarray(text);
        const groups = groupArray(arrs, 7);
        arrDataPDF.unshift(...groups);
        createTable(groups);
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
        tableData.forEach((lot, index) => {
            setTimeout(() => {
                const col = $(`<tr class="text-end"></tr>`);
                col.html(`<td class="text-center">${lot[0]}</td>
                    <td class="text-center">${lot[1]}</td>
                    <td>${lot[2]}</td>
                    <td>${lot[3]}</td>
                    <td class="col-1 text-center">${lot[4]}</td>
                    <td class="col-1">${lot[5]}</td>
                    <td>${lot[6]}</td>`);
                $('#tableData').append(col);
                col.hide();
                //ລວມເງິນທັງໝົດ
                const sums = calculator(lot);
                sales += sums.sales;
                award += sums.award;
                calpercent += sums.calpercent;
                amount += sums.amount;
                showProgressBar(tableData.length, index + 1);
                if (tableData.length == index + 1) {
                    //ແຖວລວມເງິນທັງໝົດ
                    $("#tableData").append(`<tr class="text-end"><td colspan="2" class="text-center">ລ່ວມທັງໝົດ</td>
                        <td>${myMoney(sales)}</td>
                        <td>${myMoney(award)}</td>
                        <td class="text-center">-</td>
                        <td>${myMoney(calpercent)}</td>
                        <td>${myMoney(amount)}</td>
                    </tr>`);
                    //ສະແດງປຸ່ມ
                    $("#btnscan").removeAttr("disabled");
                    isShowButton();
                    showCurrentPage(500, 1);
                    PaginationEvents(tableData, 500);
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
            $("#txtno").val(splitText[1]);
            $("#datepicker").val(splitText[3]);
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
</script>