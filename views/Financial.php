<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <h4 class="text-center mb-3">ງວດທີ: <span id="mslotNo"></span> ວັນທີ: <span id="mslotDate"></span></h4>
    <form id="formsearch">
        <div class="d-flex justify-content-end gap-4 mb-3">
            <div class="">
                <select class="form-select" aria-label="Default select example" name="provinceID" id="cbprovince" required>
                    <?php
                    include("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <input type="search" class="form-control" name="search" placeholder="🔍 ຄົ້ນຫາ">
                <button class="btn btn-primary" type="submit">ສະແດງ</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ແຂວງ</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ປ້ອນ</th>
            </tr>
        </thead>
        <tbody id="tbdata">

        </tbody>
    </table>
</div>
<?php
include_once("./database/lotteryNo.php");
$getlotteryNo = getLotteryNo()['lotteryNo'];
$getlotteryDate = date("d/m/Y", strtotime(getLotteryNo()['lotDate']));
$scanPath = '/lottery/views/Scaner.php';
?>
<script src="./script/html5-qrcode.min.js"></script>
<script>
    const formsearch = $("#formsearch");

    const show = async () => {
        $("#mslotNo").text("<?= $getlotteryNo ?>");
        $("#mslotDate").text("<?= $getlotteryDate ?>");
        const res = await fetch(`./api/unitAPI.php?api=unitslimit&limit=30`);
        const unitData = await res.json();
        const units = unitData.data;
        CreateTableData(units);
    }

    // Onload
    show();

    const unformatMoney = (money) => {
        return money.replace(/[^0-9.-]+/g, "");
    }

    const formatMoney = (money) => {
        const formattedValue = new Intl.NumberFormat('en-US').format(unformatMoney(money));
        return formattedValue;
    }


    formsearch.on("submit", (e) => {
        e.preventDefault();
        const formData = formsearch.serialize();
        $("#tbdata").html("");
        $.post("./api/unitAPI.php?api=search", formData, (res) => {
            const units = res.data;
            if (units.length > 0) {
                CreateTableData(units);
            } else {
                $("#tbdata").html("<tr><td colspan='4' class='text-center'>ບໍ່ພົບຂໍ້ມູນໜ່ວຍ</td></tr>");
            }
        })
    });

    const CreateTableData = (units) => {
        units.forEach(async (unit, index) => {
            const tr = $(`<tr id="col${unit['unitID']}"></tr>`);
            const action = $("<td class='text-center'></td>");
            tr.html(`
                    <th scope="row" class="text-center">${index+1}</th>
                    <td>${unit['pname']}</td>
                    <td>${unit['unitName']}</td>
                `);
            // API checking
            $.get(`./api/FinancialAPI.php?api=getfinancial&id=<?= $_GET['lotid'] ?>&UnitID=${unit['unitID']}`, (issave) => {
                if (issave.data) {
                    //update button
                    action.append(createButtonUpdate(unit));
                } else {
                    // create button
                    action.append(createButtonAdd(unit, index, units));
                }
            });
            tr.append(action);
            $('#tbdata').append(tr);
            //Color table
            const column = $(`#col${unit['unitID']}`);
            await ColorTable(unit['unitID'], column);
        });
    }

    const createButtonAdd = (unit, index, units) => {
        const buttonAdd = $("<button class='btn btn-primary btn-sm'>ປ້ອນ</button>");
        buttonAdd.click(() => {
            AlertAddLottery(unit, index, units);
        });
        return buttonAdd;
    }

    const createButtonUpdate = (unit) => {
        const button = $("<button class='btn btn-success btn-sm ms-1'>ແກ້ໄຂ</button>");
        button.click(() => {
            $.get(`./api/FinancialAPI.php?api=getfinancialbyunitid&unitID=${unit['unitID']}&lotteryID=<?= getLotteryNo()['lotteryID'] ?>`, (res) => {
                if (res.state) {
                    const financial = res.data
                    AlertUpdate(financial);
                }
            });

        })
        return button;
    }


    const AlertAddLottery = (unit, index, units) => {
        const withdrawn = unit['withdrawn'];
        const isMinusAward = withdrawn != 0;
        Swal.fire({
            title: unit['unitName'],
            html: `
                        <form class="px-2" id="frmlottery">
                            <input type="text" name="unitID" value="${unit['unitID']}" hidden>
                            <input type="text" name="userID" value="<?php print_r($_SESSION['user'][0]['userID']) ?>" hidden>
                            <div class="d-flex gap-2 mb-3">
                                <div class="w-50">
                                    <label for="" class="form-label w-100 text-start">ງວດທີ</label>
                                    <input type="text" class="form-control" placeholder="ງວດທີ" value="<?= getLotteryNo()['lotteryNo'] ?>" disabled>
                                </div>
                                <div class="w-75">
                                    <label for="" class="form-label w-100 text-start">ວັນທີ</label>
                                    <input type="date" class="form-control" value="<?php echo date("Y-m-d") ?>" placeholder="ງວດທີ" name="SaveDate">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ຍອດຂາຍ</label>
                                <input type="text" class="form-control border-danger" placeholder="ປ້ອນຍອດຂາຍ" id="price" name="Sales" required>
                            </div>
                            <div class="mb-3 d-flex gap-2">
                                <div class="w-50">
                                    <label for="" class="form-label w-100 text-start">ຫັກເບີເຊັນ</label>
                                    <input type="text" class="form-control" placeholder="ປ້ອນຫັກເບີເຊັນ" id="txtshowpercentage" disabled>
                                    <input type="number" class="form-control" placeholder="ປ້ອນຫັກເບີເຊັນ" id="percentage" name="Percentage" hidden>
                                </div>
                                <div class="w-50">
                                    <label for="" class="form-label w-100 text-start">ເປັນເງິນ</label>
                                    <input type="text" class="form-control" placeholder="ເປັນເງິນ" id="amount" name="amount" disabled required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ເງິນຕ້ອງຖອກ</label>
                                <input type="text" class="form-control" placeholder="ເງິນຕ້ອງຖອກ" id="money" name="money" disabled required>
                            </div>
                            <div class="mb-3">
                                <div>
                                    <label for="" class="form-label w-100 text-start">ລາງວັນ</label>
                                    <input type="text" class="form-control border-danger" placeholder="ລາງວັນ" id="lotPrice" name="Award" required>
                                </div>
                            </div>
                            <div class="mb-3 d-flex gap-1">
                                <div class="flex-fill">
                                    <label for="Codeaward" class="form-label w-100 text-start">ເລກທີລາງວັນ</label>
                                    <input type="text" class="form-control border-danger" placeholder="ເລກທີລາງວັນ" id="Codeaward" name="Awardno">
                                </div>
                                <div class="mt-auto">
                                    <a href="<?= $scanPath ?>" target="_blank" class="btn btn-info"><i class='bx bx-scan' ></i></a>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="form-label w-100 text-start">ຍອດເຫຼືອ ${isMinusAward?"":" <span class='text-warning'>(ບໍ່ລົບລາງວັນ)</span>"}</label>
                                <input type="text" class="form-control" placeholder="ຍອດເຫຼືອ" id="total" name="total" disabled>
                            </div>
                            <div>
                                <button class="btn btn-primary" type="submit">ບັນທຶກ</button>
                            </div>
                        </form>
                        `,
            showConfirmButton: false,
            showCloseButton: true,
            focusCancel: false,
        });

        $("#price").focus();

        getPercentage(unit['unitID']);

        const txtPrice = $('#price'); //ປ້ອນຍອດຂາຍ
        const txtpercent = $('#percentage'); //ຫັກເບີເຊັນ
        const txtPercentPrie = $('#amount'); //ເປັນເງິນ
        const txtMoney = $('#money'); //ເງິນຕ້ອງຖອກ
        const txtAward = $('#lotPrice'); //ລາງວັນ
        const txttotal = $('#total'); //ຍອດເຫຼືອ
        const frmlottery = $('#frmlottery');

        // ຈຳນວນເງິນມີຈຸດ
        const FormatText = (element) => {
            const text = unformatMoney(element.val());
            const format = formatMoney(text);
            element.val(format);
        }

        //ຄິດໄລຄ່າເປີເຊັນ
        const Calpercentage = () => {
            const percentage = txtpercent.val();
            const Sales = unformatMoney(txtPrice.val());
            const amount = (Sales * percentage) / 100;
            txtPercentPrie.val(amount);
            FormatText(txtPercentPrie);
        }

        // ສະແດງຜົນລັບ
        const CalTotal = () => {
            const Sales = unformatMoney(txtPrice.val());
            const PercentPrice = unformatMoney(txtPercentPrie.val());
            const amount = Sales - PercentPrice;
            txttotal.val(amount);
            txtMoney.val(amount);
            FormatText(txtMoney);
            FormatText(txttotal);
        }

        // ຄິດໄລ່ຫັກຍອດເປີເຊັນ
        const ColTotalWithPercentage = () => {
            if (isMinusAward) {
                const Price = unformatMoney(txtMoney.val());
                const award = unformatMoney(txtAward.val())
                const amount = Price - award;
                txttotal.val(amount);
                FormatText(txttotal);
            }
        }

        txtPrice.on("keyup", () => {
            FormatText(txtPrice);
            Calpercentage();
            CalTotal();
            ColTotalWithPercentage();
        });

        txtAward.on('keyup', () => {
            FormatText(txtAward);
            ColTotalWithPercentage();
        });


        frmlottery.on("submit", (e) => {
            e.preventDefault();
            const formData = frmlottery.serialize();
            $.post("./api/FinancialAPI.php?api=create&id=<?= $_GET['lotid'] ?>", formData, (res) => {
                Swal.fire({
                    position: "center",
                    icon: res.data,
                    text: res.message,
                    showCancelButton: true,
                    confirmButtonText: "ໜ່ວຍຕໍ່ໄປ",
                    cancelButtonText: `ກັບຄືນ`,
                    didClose: () => {
                        location.reload();
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const nextSize = units.length;
                        const nextIndex = index + 1;
                        if (nextSize > nextIndex) {
                            AlertAddLottery(units[nextIndex], nextIndex, units);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        location.reload();
                    }
                });
            });
        });
    }


    // ແກ້ໄຂຂໍ້ມູນ
    const AlertUpdate = (financial) => {
        const withdrawn = financial['withdrawn'];
        const isMinusAward = withdrawn != 0;
        //======= Calculator ==========
        const getSale = Number(financial['Sales']);
        const getPercentage = Number(financial['Percentage']);
        const getAward = Number(financial['Award']);
        const getCalPercent = (getSale * getPercentage) / 100;
        const getMoney = getSale - getCalPercent;
        const getTotal = isMinusAward ? getMoney - getAward : getMoney;

        Swal.fire({
            title: financial['unitName'],
            html: `
                        <form class="px-2" id="frmlottery">
                            <input type="text" name="unitID" value="${financial['unitID']}" hidden>
                            <input type="text" name="userID" value="<?php print_r($_SESSION['user'][0]['userID']) ?>" hidden>
                            <div class="d-flex gap-2 mb-3">
                                <div class="w-50">
                                    <label for="" class="form-label w-100 text-start">ງວດທີ</label>
                                    <input type="text" class="form-control" placeholder="ງວດທີ" value="${financial['lotteryNo']}" disabled>
                                </div>
                                <div class="w-75">
                                    <label for="" class="form-label w-100 text-start">ວັນທີ</label>
                                    <input type="date" class="form-control" value="${financial['SaveDate']}" placeholder="ງວດທີ" name="SaveDate">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ຍອດຂາຍ</label>
                                <input type="text" class="form-control border-danger" placeholder="ປ້ອນຍອດຂາຍ" id="price" value="${formatMoney(""+getSale)}" name="Sales" required>
                            </div>
                            <div class="mb-3 d-flex gap-2">
                                <div class="w-50">
                                    <label for="" class="form-label w-100 text-start">ຫັກເບີເຊັນ</label>
                                    <input type="text" class="form-control" placeholder="ປ້ອນຫັກເບີເຊັນ" name="perc"  disabled value="${getPercentage}%">
                                    <input type="number" class="form-control" placeholder="ປ້ອນຫັກເບີເຊັນ" id="percentage" value="${getPercentage}" name="Percentage" hidden>
                                </div>
                                <div class="w-50">
                                    <label for="" class="form-label w-100 text-start">ເປັນເງິນ</label>
                                    <input type="text" class="form-control" placeholder="ເປັນເງິນ" id="amount" value="${formatMoney(""+getCalPercent)}" name="amount" disabled required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ເງິນຕ້ອງຖອກ</label>
                                <input type="text" class="form-control" placeholder="ເງິນຕ້ອງຖອກ" id="money" name="money" value="${formatMoney(""+getMoney)}" disabled required>
                            </div>
                            <div class="mb-3">
                                <div>
                                    <label for="" class="form-label w-100 text-start">ລາງວັນ</label>
                                    <input type="text" class="form-control border-danger" placeholder="ລາງວັນ" id="lotPrice" value="${formatMoney(""+getAward)}" name="Award" required>
                                </div>
                            </div>
                            <div class="mb-3 d-flex gap-1">
                                <div class="flex-fill">
                                    <label for="Codeaward" class="form-label w-100 text-start">ເລກທີລາງວັນ</label>
                                    <input type="text" class="form-control border-danger" placeholder="ເລກທີລາງວັນ" id="Codeaward" value="${financial['AwardNo']}" name="Awardno">
                                </div>
                                <div class="mt-auto">
                                    <a href="<?= $scanPath ?>" target="_blank" class="btn btn-info"><i class='bx bx-barcode'></i></a>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="form-label w-100 text-start">ຍອດເຫຼືອ ${isMinusAward?"":" <span class='text-warning'>(ບໍ່ລົບລາງວັນ)</span>"}</label>
                                <input type="text" class="form-control" placeholder="ຍອດເຫຼືອ" id="total" value="${formatMoney(""+getTotal)}" name="total" disabled>
                            </div>
                            <div>
                                <button class="btn btn-success w-100" type="submit"><i class='bx bxs-edit'></i> ແກ້ໄຂ</button>
                            </div>
                        </form>
                        `,
            showConfirmButton: false,
            showCloseButton: true,
            focusCancel: false
        });

        $("#price").focus();

        const txtPrice = $('#price'); //ປ້ອນຍອດຂາຍ
        const txtpercent = $('#percentage'); //ຫັກເບີເຊັນ
        const txtPercentPrie = $('#amount'); //ເປັນເງິນ
        const txtMoney = $('#money'); //ເງິນຕ້ອງຖອກ
        const txtAward = $('#lotPrice'); //ລາງວັນ
        const txttotal = $('#total'); //ຍອດເຫຼືອ
        const frmlottery = $('#frmlottery');

        // ຈຳນວນເງິນມີຈຸດ
        const FormatText = (element) => {
            const text = unformatMoney(element.val());
            const format = formatMoney(text);
            element.val(format);
        }

        //ຄິດໄລຄ່າເປີເຊັນ
        const Calpercentage = () => {
            const percentage = txtpercent.val();
            const Sales = unformatMoney(txtPrice.val());
            const amount = (Sales * percentage) / 100;
            txtPercentPrie.val(amount);
            FormatText(txtPercentPrie);
        }

        // ສະແດງຜົນລັບ
        const CalTotal = () => {
            const Sales = unformatMoney(txtPrice.val());
            const PercentPrice = unformatMoney(txtPercentPrie.val());
            const amount = Sales - PercentPrice;
            txttotal.val(amount);
            txtMoney.val(amount);
            FormatText(txtMoney);
            FormatText(txttotal);
        }

        // ຄິດໄລ່ຫັກຍອດເປີເຊັນ
        const ColTotalWithPercentage = () => {
            if (isMinusAward) {
                const Price = unformatMoney(txtMoney.val());
                const award = unformatMoney(txtAward.val())
                const amount = Price - award;
                txttotal.val(amount);
                FormatText(txttotal);
            }
        }

        txtPrice.on("keyup", () => {
            FormatText(txtPrice);
            Calpercentage();
            CalTotal();
            ColTotalWithPercentage();
        });

        txtAward.on('keyup', () => {
            FormatText(txtAward);
            ColTotalWithPercentage();
        });

        frmlottery.on("submit", (e) => {
            e.preventDefault();
            const formData = frmlottery.serialize();
            $.post(`./api/FinancialAPI.php?api=update&id=${financial['FinancialID']}`, formData, (res) => {
                if (res.state) {
                    Swal.fire({
                        position: "center",
                        icon: res.data,
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).finally(() => {
                        location.reload();
                    });
                }
            });
        });
    }

    const ColorTable = async (UnitID, column) => {
        const res = await fetch(`./api/FinancialAPI.php?api=getfinancial&id=<?= $_GET['lotid'] ?>&UnitID=${UnitID}`);
        const data = await res.json();
        if (data.data) {
            column.attr("class", "table-success");
        }
    }

    const getPercentage = async (UnitID) => {
        const res = await fetch(`./api/unitAPI.php?api=percentage&unitid=${UnitID}`);
        const data = await res.json();
        const percentage = $("#percentage");
        const txtshowpercentage = $("#txtshowpercentage");
        percentage.val(data.data);
        txtshowpercentage.val(data.data);
    }
</script>