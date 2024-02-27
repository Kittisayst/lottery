<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <?php include_once("./database/getPaymentNo.php") ?>
    <div class="mb-3">
        <button class="btn btn-secondary" id="btnback"><i class='bx bx-arrow-back'></i> ກັບຄືນ</button>
    </div>
    <div class="d-flex gap-1">
        <div class="col border rounded-2 bg-white p-2">
            <h4 class="text-center mb-3 mt-2">ລາຍການຂໍ້ມູນງວດທີຕ້ອງຖອກ</h4>
            <ul id="listFinancial" class="list-group">
            </ul>
        </div>
        <form class="border rounded-2 bg-white col-5 py-2 px-2" id="savePaylist">
            <div class="d-flex gap-2 mb-3">
                <input type="text" value="<?= getBillNo(); ?>" name="billno" hidden>
                <input type="text" value="<?php print_r($_SESSION['user'][0]['userID']) ?>" id="txtUserID" name="UserID" hidden>
                <div class="w-100">
                    <label class="form-label">ເລກທີ</label>
                    <input type="text" class="form-control" placeholder="ເລກທີ" value="KF<?= getBillNo(); ?>" disabled>
                </div>
                <div class="w-100">
                    <label class="form-label">ວັນທີ</label>
                    <input type="date" class="form-control" placeholder="ວັນທີ" value="<?= date("Y-m-d"); ?>" disabled>
                </div>
            </div>
            <div class="my-2 p-1">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">#</th>
                            <th scope="col">ເງິນສົດ</th>
                            <th scope="col">ເງິນໂອນ</th>
                            <th scope="col">ໝາຍເຫດ</th>
                            <th scope="col">ລົບ</th>
                        </tr>
                    </thead>
                    <tbody id="paymentData">
                        <tr>
                            <td colspan="5" class="text-center">....ເລືອກງວດທີ່ຕ້ອງການຖອກເງິນ....</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="my-2 text-end me-1 fs-5">
                <span class="" id="showAmount">

                </span>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-100" disabled id="btnsave"><i class='bx bxs-save'></i> ບັນທຶກ</button>
            </div>
        </form>
    </div>
</div>
<script>
    const PaymentData = [];

    $("#btnback").click(() => {
        history.back();
    });

    $.get(`./api/FinancialAPI.php?api=getlistPayment&unitid=<?= $_GET['unitid'] ?>`, (res) => {
        // console.log(res);
        const financials = res.data;
        financials.forEach((financial, index) => {
            const Money = Calculator(financial);
            const isActive = index === 0 ? "" : "disabled";
            const button = $(`<button class="btn btn-sm btn-success w-100" ${isActive}><i class='bx bxs-wallet-alt'></i> ຖອກເງິນ</button>`);
            const list = $(`
            <li class="list-group-item ${isActive}" aria-disabled="true" id="fn${index}">
            <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">ງວດທີ ${financial.lotteryNo} (${financial.lotteryCorrect})</h5>
            <small>${jDateformat(financial.lotDate)}</small>
            </div>
            <p class="mb-1">
                <span class="me-5">ຍອດຂາຍ: ${FMoney(financial.Sales)}</span>
                <span class="me-5">ເປີເຊັນ: ${financial.Percentage}%</span>
                <span>ເປັນເງິນ: ${FMoney(Money.calpercent)}</span>
            </p>
            <p>
                <span class="me-5">ເງິນລາງວັນ: ${FMoney(Money.calRealPrice)}</span>
                <span class="badge text-bg-danger me-2 fs-6">ເງິນຕ້ອງຖອກ: ${FMoney(Money.calAmount)}</span>
                <span class="badge text-bg-warning">${Money.isWithdraw}</span>
            </p>
            </li>`);
            list.append(button);
            $("#listFinancial").append(list);
            button.click(() => {
                Swal.fire({
                    title: `ຈຳນວນເງິນ: ${FMoney(Money.calAmount)} ກີບ`,
                    html: `
                    <form id="frmPayment" class="p-2">
                        <div class="mb-3">
                            <label for="" class="form-label text-start w-100">ເງິນສົດ</label>
                            <input type="text" class="form-control" placeholder="ເງິນສົດ" name="cash" id="txtcash">
                        </div>
                        <div class="mb-3">
                        <label for="" class="form-label text-start w-100">ເງິນໂອນ</label>
                            <input type="text" class="form-control" placeholder="ເງິນໂອນ" name="transfer" id="txttransfer">
                        </div>
                        <div class="mb-3">
                        <label for="" class="form-label text-start w-100">ອື່ນໆ</label>
                            <input type="text" class="form-control" placeholder="ອື່ນໆ" name="other" id="txtother">
                        </div>
                        <div class="form-label text-start w-100 mb-3">
                            <label for="txtcomment" class="form-label">ໝາຍເຫດ</label>
                            <textarea class="form-control" rows="3" name="comment" id="txtcomment"></textarea>
                        </div>
                        <div class="mb-2">
                            <h5 class="text-danger" id="showTotal">ຈຳນວນເງິນຄ້າງ: ${FMoney(Money.calAmount)}</h5>
                        </div>
                        <div class="mb-3">
                            <span id="mswarning" class="badge text-bg-warning w-100"></span>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success w-100"><i class='bx bxs-save'></i> ບັນທຶກ</button>
                        </div>
                    </form>`,
                    showConfirmButton: false,
                    showCloseButton: true,
                    focusCancel: false,
                });
                const txtcash = $("#txtcash");
                const txttransfer = $("#txttransfer");
                const txtother = $("#txtother");

                const EventHandlerCalculator = (element) => {
                    element.on("keyup", () => {
                        FormatText(element);
                        const cashmoney = UnMoney(txtcash.val());
                        const transfermoney = UnMoney(txttransfer.val());
                        const othermoney = UnMoney(txtother.val());
                        const sumMoney = cashmoney + transfermoney + othermoney;
                        const result = Money.calAmount - sumMoney;
                        $("#showTotal").text("ຈຳນວນເງິນຄ້າງ: " + FMoney(result));
                    });
                }

                EventHandlerCalculator(txtcash);
                EventHandlerCalculator(txttransfer);
                EventHandlerCalculator(txtother);

                const frmPayment = $("#frmPayment");
                frmPayment.on("submit", (e) => {
                    e.preventDefault();
                    const formdata = frmPayment.serializeArray();
                    if (formdata[0].value == "" && formdata[1].value == "" && formdata[2].value == "" && formdata[3].value == "") {
                        $("#mswarning").text("ກະລຸນາປ້ອນຈຳນວນເງິນ.....!");
                        const hidems = setTimeout(() => {
                            $("#mswarning").text("");
                        }, 4000);
                    } else {
                        const cash = UnMoney(formdata[0].value);
                        const transfer = UnMoney(formdata[1].value);
                        const other = UnMoney(formdata[2].value);
                        const sum = cash + transfer + other;
                        if (sum > Money.calAmount) {
                            $("#mswarning").text("ຈຳນວນເງິນຫຼາຍກ່ວາຈຳນວນທີ່ຕ້ອງຖອກ");
                            const hidems = setTimeout(() => {
                                $("#mswarning").text("");
                            }, 4000);
                        } else {
                            list.attr("class", "d-none");
                            PaymentData.push({
                                "el": list,
                                "data": financial,
                                "form": formdata
                            });
                            showPaymentData();
                            List_Disabled(index);
                            Swal.close();
                        }
                    }
                });

            })
        });
    });


    const showPaymentData = () => {
        $("#paymentData").html(``);
        let amount = 0;
        PaymentData.forEach((payment, index) => {
            const data = payment.data;
            const formdata = payment.form;
            const cash = UnMoney(formdata[0].value);
            const transfer = UnMoney(formdata[1].value);
            const orther = UnMoney(formdata[2].value);
            const comment = formdata[3].value;

            const btnDelete = $(`<button class="btn btn-sm btn-danger"><i class='bx bxs-trash'></i></button>`);
            const rowDel = $(`<td class="text-center"></td>`);
            const row = $(`<tr>
            <td class="text-center">${index+1}</td>
            <td class="text-end">${FMoney(cash)}</td>
            <td class="text-end">${FMoney(transfer)}</td>
            <td>${comment}</td>
            </tr>`);
            row.append(rowDel);
            rowDel.append(btnDelete);
            btnDelete.click(() => {
                payment.el.attr("class", "list-group-item");
                PaymentData.splice(payment, 1);
                showPaymentData();
                if (PaymentData.length == 0) {
                    $("#paymentData").html(`<tr><td colspan="5" class="text-center">....ເລືອກງວດທີ່ຕ້ອງການຖອກເງິນ....</td></tr>`);
                }
            });
            amount += cash + transfer + orther;
            $("#paymentData").append(row);
        });

        $("#showAmount").text(`ລວມທັງໝົດ: ${FMoney(amount)} ກີບ`);
        if (PaymentData.length > 0) {
            $("#btnsave").removeAttr('disabled');
        }
    }

    const savePaylist = $("#savePaylist");
    savePaylist.on("submit", (e) => {
        e.preventDefault();
        savePayment(PaymentData);
    });

    const savePayment = (PaymentData) => {
        const data = {
            UserID: $("#txtUserID").val()
        };
        console.log(PaymentData);
        // $.post(`./api/PaymentAPI.php?api=create`, data, (res) => {
        //     console.log(res);
        //     if (res.state) {
        //         console.log(PaymentData);
        //         PaymentData.forEach(list => {
        //             const fromData = {
        //                 "paymentID": res.data,
        //                 "FinancialID": list.data.FinancialID,
        //                 "Cash": list.form[0].value,
        //                 "Transfer": list.form[1].value,
        //                 "Other": list.form[2].value,
        //                 "Comment": list.form[3].value
        //             };
        //             console.log(fromData);
        //             SavePaylist(fromData);
        //         });
        //     }
        // });
    }

    const SavePaylist = (data) => {
        console.log("savePaylist");
        $.post('./api/PaymentlistAPI.php?api=create', data, (res) => {
            console.log(res);
        });
    }

    const List_Disabled = (index) => {
        const selectID = `#fn${Number(index)+1}`;
        const list = $(selectID);
        const button = $(selectID + " button");
        button.removeAttr('disabled');
        list.removeClass('disabled');
    }

    const Calculator = (financial) => {
        const Sales = Number(financial.Sales);
        const percentage = Number(financial.Percentage);
        const calPercent = (Sales * percentage) / 100;
        const RealPrice = Sales - calPercent;
        const Award = Number(financial.Award);
        const IsWithdraw = Number(financial.withdrawn);
        const Amount = IsWithdraw == 0 ? RealPrice : RealPrice - Award;
        return {
            calpercent: calPercent,
            calRealPrice: RealPrice,
            calAmount: Amount,
            isWithdraw: IsWithdraw == 0 ? "ບໍ່ລົບລາງວັນ" : ""
        };
    }

    const FMoney = (money) => {
        const formattedValue = new Intl.NumberFormat('en-US').format(money);
        return formattedValue;
    }

    const UnMoney = (money) => {
        return Number(money.replace(/[^0-9.-]+/g, ""));
    }


    function jDateformat(inputDate) {
        var dateParts = inputDate.split("-");
        var formattedDate = dateParts[2] + "/" + dateParts[1] + "/" + dateParts[0];
        return formattedDate;
    }

    // ຈຳນວນເງິນມີຈຸດ
    const FormatText = (element) => {
        const text = UnMoney(element.val());
        const format = FMoney(text);
        element.val(format);
    }
</script>