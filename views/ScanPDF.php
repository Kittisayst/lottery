<div class="container content">
    <?php
    require_once ("./views/Alert.php");
    require_once ("./database/ScanPDFTitle.php");
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
                    <button type="submit" class="btn btn-primary" id="btnshow">
                        <i class="bi bi-search"></i> ສະແດງ
                    </button>
                    <a class="btn btn-info" href="?page=scanpayment&id=<?= $_GET['id'] ?>&limit=100&pagination=1"><i
                            class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>
            <div>
                <button class="btn btn-danger" id="btnsavepdf" disabled>
                    <i class="bi bi-file-earmark-pdf-fill"></i> Save PDF
                </button>
                <button class="btn btn-success" id="btnsaveExcel" disabled>
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Save Excel
                </button>
                <button class="btn btn-primary ms-5" id="btnSave" disabled>
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
<script>

    $(document).ready(function () {
        loadData(`./database/ReaderSalePDFTable.php?id=<?= $_GET['id'] ?>`);
    });

    const loadData = async (url) => {
        //ສະແດງໂຫຼດຕາຕະລາງ
        $('#tableData').html(`
            <tr class="text-center">
                <td colspan='7'>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">ກຳລັງໂຫຼດຂໍ້ມູນຈາກ PDF ກະລຸນາລໍຖ້າ!</span>
                </td>
            </tr>`);
        //ລ້າງປຸ່ມ
        $("#pagilist").html("");
        //ດຶງຂໍ້ມູນຈາກ API
        const fetching = await fetch(url);
        const res = await fetching.json();
        const convert = JSON.parse(res.data);
        $("#tableData").html("");
        let Sales = 0;
        let Award = 0;
        let Price = 0;
        let Amount = 0;

        convert.forEach((item, index) => {
            const saleData = typeof item === "string" ? JSON.parse(item) : item;
            CreateTable(saleData, index);
            //ຄິດໄລ່ລວມເງິນ
            const sum = convertMoney(saleData);
            Sales += sum.Sales;
            Award += sum.Award;
            Price += sum.Price;
            Amount += sum.Amount;
        });
        CreateRowTotal(Sales, Award, Price, Amount);
        if (convert.length >= 500) {
            const rowviews = 500;
            showCurrentPage(rowviews, 1);
            PaginationEvents(convert, rowviews);
        } else {
            $('#tableData tr').show();
        }
    }


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
        isShowButton();
    }

    function showCurrentPage(pageSize, currentPage) {
        $('#tableData tr').hide();
        startIndex = (currentPage - 1) * pageSize;
        endIndex = startIndex + pageSize;
        $('#tableData tr').slice(startIndex, endIndex).show();
    }

    function PaginationEvents(tableData, buttons) {
        const countButton = tableData.length / buttons;
        console.log(countButton);
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

    const convertMoney = (data) => {
        const Sales = str_number(data['Sales']);
        const Award = str_number(data['Award']);
        const Price = str_number(data['Price']);
        const Amount = str_number(data['Amount']);
        return { "Sales": Sales, "Award": Award, "Price": Price, "Amount": Amount };
    }

    $("#frmshowUint").submit((e) => {
        e.preventDefault();
        const frm = $("#frmshowUint").serializeArray();
        loadData(`./database/ReaderSalePDFTable.php?id=<?= $_GET['id'] ?>&unitID=${frm[1].value}`);
    });

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

    //Save PDF Data
    $("#btnSave").on("click", () => {
        const provinceText = $("#cbProvince option:selected").text();
        const unittext = $('#cbUnit option:selected').text();
        const lotdate = $("#lotdate").text();
        // ຍອດຂາຍ ແຂວງ​ໄຊຍະບູລີ ໜ່ວຍ​ ທ.​ເປ​ ວັນທີ່.​ 19/04/2024
        const commentText = `ຍອດຂາຍ ${provinceText} ໜ່ວຍ ${unittext} ວັນທີ່ ${lotdate}`;
        Swal.fire({
            html: `
            <div class="mb-4 p-3">
                <h4 class="fw-bold">${commentText}</h4>
            </div>
            <form class="p-1" id="frmSave">
                <div class="mb-3">
                    <label for="cblot" class="form-label w-100 text-start">ງວດທີ</label>
                    <select class="form-select" name="loteryID" id="cblot" required>
                        <?php
                        require_once ("./database/LotteryOption.php");
                        ?>
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
            const datas = {
                "salePDFID": <?= $_GET['id'] ?>,
                "unitID": <?= $_GET['unitID'] ?? "''" ?>,
                "lotteryID": frm[0].value,
                "comment": frm[1].value,
                "pdfData": JSON.stringify(createPDFData())
            };
            $.post(`./api/PDFDataAPI.php?api=create`, datas, (res) => {
                if (res.state) {
                    Swal.fire({
                        title: res.message,
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: "ປີ້ນໃບລາຍງານ ການຂາຍ",
                        cancelButtonText: "ຄົ້ນຫາຂໍ້ມູນ PDF ໜ້ານີ້ຕໍ່"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = `?page=printsalepdf&id=${res.data}`;
                        } else {
                            Swal.close();
                        }
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

</script>