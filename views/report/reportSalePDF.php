<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="d-flex justify-content-between w-100 bg-light p-3">
        <form class="d-flex align-items-center gap-2 me-auto" id="frmshowUint">
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
            <div class="d-flex align-items-center gap-2 col-1s">
                <label for="cblotteryID" class="form-label w-50">ງວດທີ</label>
                <select class="form-select" name="lotteryID" id="cblotteryID">
                    <?php
                    require_once "./database/LotteryOption.php";
                    ?>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" id="btnshow">
                    <i class="bi bi-search"></i> ສະແດງ
                </button>
            </div>
        </form>
        <div>
            <a class="btn btn-success" href="?page=salepdf">
                <i class="bi bi-plus-circle-fill"></i> ອ່ານ PDF ການຂາຍ
            </a>
        </div>
    </div>
    <table class="table table-bordered table-striped mt-2" id="tbsales">
        <thead>
            <tr class="text-center align-middle">
                <th scope="col">ລຳດັບ</th>
                <th scope="col">ຫົວຂໍ້</th>
                <th scope="col">ໝາຍເຫດ</th>
                <th scope="col" style="width: 180px;">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tableData">
            <?php
            require_once "./database/TableReportSalePDF.php";
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalfinancial" tabindex="-1" aria-labelledby="modalfinancialLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4 fw-bold text-center w-100" id="modalfinancialLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex">
                <div class="col border p-3">
                    <table class="table table-bordered table-striped mt-2 table-sm" id="tbsales">
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
                        <tbody id="tableSaleData">

                        </tbody>
                    </table>
                </div>
                <div class="col-3 border p-3">
                    <form id="frmSaveFinancial" class="mt-2">
                        <div class="d-flex gap-2 mb-3">
                            <div class="w-50">
                                <label for="txtlotno" class="form-label">ງວດທີ</label>
                                <input type="text" class="form-control text-center" placeholder="ງວດທີ" id="txtlotno"
                                    value="" disabled>
                            </div>
                            <div class="w-75">
                                <label for="txtDate" class="form-label">ວັນທີ</label>
                                <input type="date" class="form-control text-center" value="<?php echo date("Y-m-d") ?>"
                                    placeholder="ງວດທີ" name="SaveDate" id="txtDate">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="txtSales" class="form-label">ຍອດຂາຍ</label>
                            <input type="text" class="form-control text-end" placeholder="ປ້ອນຍອດຂາຍ" id="txtSales"
                                name="Sales" required disabled>
                        </div>
                        <div class="mb-3 d-flex gap-2">
                            <div class="w-50">
                                <label for="txtpercentage" class="form-label">ຫັກເບີເຊັນ</label>
                                <input type="text" class="form-control text-center" placeholder="ປ້ອນຫັກເບີເຊັນ"
                                    id="txtpercentage" disabled>
                            </div>
                            <div class="w-50">
                                <label for="txtPrice" class="form-label">ເປັນເງິນ</label>
                                <input type="text" class="form-control text-end" placeholder="ເປັນເງິນ" id="txtPrice"
                                    name="amount" disabled required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="txtmoney" class="form-label">ເງິນຕ້ອງຖອກ</label>
                            <input type="text" class="form-control text-end" placeholder="ເງິນຕ້ອງຖອກ" id="txtmoney"
                                name="money" disabled required>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="txtAward" class="form-label">ລາງວັນ</label>
                                <input type="text" class="form-control text-end" placeholder="ລາງວັນ" id="txtAward"
                                    name="Award" required>
                            </div>
                        </div>
                        <div class="mb-3 d-flex gap-1">
                            <div class="flex-fill">
                                <label for="txtCodeaward" class="form-label">ເລກທີລາງວັນ</label>
                                <input type="text" class="form-control text-center" placeholder="ເລກທີລາງວັນ"
                                    id="txtCodeaward" name="Awardno">
                            </div>
                            <div class="mt-auto">
                                <a href="" target="_blank" class="btn btn-info"><i class='bx bx-scan'></i></a>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="txttotal" class="form-label text-start">
                                ຍອດເຫຼືອ <span class='text-warning' id="showWidraw"></span>
                            </label>
                            <input type="text" class="form-control text-end" placeholder="ຍອດເຫຼືອ" id="txttotal"
                                name="total" disabled>
                        </div>
                        <div>
                            <button class="btn btn-primary btn-lg w-100" type="submit">
                                <i class="bi bi-floppy2-fill"></i> ບັນທຶກ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#frmshowUint").submit((e) => {
        e.preventDefault();
        const frm = $("#frmshowUint").serializeArray();
        location.href = `?page=reportsalepdf&pid=${frm[0].value}&unitID=${frm[1].value}&lotid=${frm[2].value}`;
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

    let saveData = {};

    const saveFinancial = (id) => {
        // alert(id);
        $.get(`./api/PDFDataAPI.php?api=getbyid&id=${id}`, (res) => {
            console.log(res);
            if (res.state) {
                const data = res.data;
                const pdfdata = JSON.parse(data['pdfData']);
                console.log(pdfdata);
                $("#modalfinancial").modal('show');
                $("#modalfinancialLabel").text(`ໜ່ວຍ: ${data['unitName']} ງວດທີ: ${data['lotteryNo']} ວັນທີ: ${data['lotDate']}`);
                $("#tableSaleData").html("");
                let Sales = 0;
                let Award = 0;
                let Price = 0;
                let Amount = 0;
                pdfdata.forEach((item, index) => {
                    Sales += str_number(item['Sales']);
                    const col = $("<tr class='text-end'></tr>");
                    col.html(`
                    <td class='text-center'>${index + 1}</td>
                    <td class='text-center'>${item['machineCode']}</td>
                    <td>${item['Sales']}</td>
                    <td>${item['Award']}</td>
                    <td class='text-center'>${item['Percentage']}</td>
                    <td>${item['Price']}</td>
                    <td>${item['Amount']}</td>
                    `);
                    $("#tableSaleData").append(col);
                    //ຄິດໄລ່ລວມເງິນ
                    const sum = convertMoney(item);
                    Sales += sum.Sales;
                    Award += sum.Award;
                    Price += sum.Price;
                    Amount += sum.Amount;
                });
                CreateRowTotal(Sales, Award, Price, Amount);
                //ສະແດງຂໍ້ມູນຖານປ້ອນຍອດຂາຍ
                const colPrice = (Sales * str_number(data['Percentage'])) / 100;
                $("#txtlotno").val(data['lotteryNo']);
                $("#txtSales").val(myMoney(Sales));
                $("#txtpercentage").val(data['Percentage']);
                $("#txtPrice").val(myMoney(colPrice));
                //ເງິນຕ້ອງຈ່າຍ
                const calPayment = Sales - colPrice;
                $("#txtmoney").val(myMoney(calPayment));
                $("#txttotal").val(myMoney(calPayment));
                //ຫັກລາງວັນ
                const isWithdrawn = data['withdrawn'] == "1";
                $("#txtAward").keyup((e) => {
                    FormatText($("#txtAward"));
                    const award = unMoney(e.target.value);
                    if (isWithdrawn) {
                        const total = unMoney($("#txttotal").val());
                        const coltotal = calPayment - award;
                        $("#txttotal").val(coltotal);
                        FormatText($("#txttotal").val(coltotal));
                    } else {
                        $("#showWidraw").text("(ບໍ່ລົບລາງວັນ)");
                    }
                });
                //ສ້າງການ ບັນທຶກຂໍ້ມູນ
                saveData = {
                    "unitID": data['unitID'],
                    "lotteryID": data['lotteryID'],
                    "Sales": $("#txtSales").val(),
                    "Percentage": $("#txtpercentage").val(),
                    "Award": $("#txtAward").val(),
                    "Awardno": $("#txtCodeaward").val(),
                    "SaveDate": $("#txtDate").val(),
                    "userID": <?= $_COOKIE['user'] ?>
                };

            }
        });
    }

    $("#frmSaveFinancial").submit((e) => {
        e.preventDefault();
        saveData.Award = $("#txtAward").val();
        saveData.AwardNo = $("#txtCodeaward").val();
        $.post(`./api/FinancialAPI.php?api=create&id=${saveData.lotteryID}`, saveData, (res) => {
            if (res.state) {
                Swal.fire({
                    position: "center",
                    icon: res.data,
                    title: res.message,
                    showConfirmButton: false,
                    timer: 1500
                }).finally(() => location.reload());
            } else {
                Swal.fire({
                    title: res.message,
                    icon: res.data
                });
            }
        });
    })

    const CreateRowTotal = (Sales, Award, Price, Amount) => {
        const col = $(`<tr class='text-end'>
                <td colspan='2' class='text-center'>ລ່ວມທັງໝົດ</td>
                <td>${Sales.toLocaleString()}</td>
                <td>${Award.toLocaleString()}</td>
                <td class='text-center'>-</td>
                <td>${Price.toLocaleString()}</td>
                <td>${Amount.toLocaleString()}</td>
            </tr>`);
        $("#tableSaleData").append(col);
    }

    const convertMoney = (data) => {
        const Sales = str_number(data['Sales']);
        const Award = str_number(data['Award']);
        const Price = str_number(data['Price']);
        const Amount = str_number(data['Amount']);
        return { "Sales": Sales, "Award": Award, "Price": Price, "Amount": Amount };
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

    // ຈຳນວນເງິນມີຈຸດ
    const FormatText = (element) => {
        const text = unMoney(element.val());
        const format = formatMoney(text);
        element.val(format);
    }

    const formatMoney = (money) => {
        const formattedValue = new Intl.NumberFormat('en-US').format(unMoney(money));
        return formattedValue;
    }

    const unMoney = (money) => {
        return money.replace(/[^0-9.-]+/g, "");
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