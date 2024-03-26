<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <?php
    include_once("./database/getPaymentNo.php");
    include_once("./database/user.php");
    $user = getUser($_COOKIE['user']);
    $userName = $user['UserName'];
    ?>
    <div class="mb-3">
        <a class="btn btn-secondary" href="?page=payment&pid=<?= $_GET['pid'] ?>&search=<?= $_GET['search'] ?>"><i class='bx bx-arrow-back'></i> ກັບຄືນ</a>
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
                <input type="text" value="<?php print_r($user['userID']) ?>" id="txtUserID" name="UserID" hidden>
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
                            <th scope="col">ບໍລິສັດຕິດລົບ</th>
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
            <div class="my-2 text-end me-1 py-1 fs-5">
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

    $.get(`./api/FinancialAPI.php?api=getlistPayment&unitid=<?= $_GET['unitid'] ?>`, (res) => {
        const financials = res.data;
        financials.forEach((financial, index) => {
            const Money = Calculator(financial);
            const RepayValue = Money.calAmount < 0 ? Math.abs(Money.calAmount) : 0;
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
                <span class="me-5">ເງິນລາງວັນ: ${FMoney(Money.Award)}</span>
                <span class="badge text-bg-danger me-2 fs-6" id="showMoney">ເງິນຕ້ອງຖອກ: ${FMoney(Money.calAmount)}</span>
                <span class="badge text-bg-warning">${Money.isWithdraw}</span>
            </p>
            </li>`);
            list.append(button);
            $("#listFinancial").append(list);
            //ກວດສອບປະຫວັດການຈ່າຍ
            let lastMoney = 0;
            $.get(`./api/PaymentlistAPI.php?api=getFinancialState&FinancialID=${financial.FinancialID}`, (resState) => {
                const sumMoney = Number(resState.data.sumMoney);
                lastMoney = sumMoney;
                if (sumMoney > 0) {
                    Money.calAmount = Money.calAmount - sumMoney;
                    $("#showMoney").text(`ເງິນຕ້ອງຖອກ: ${FMoney(Money.calAmount)}`);
                    $("#showMoney").attr("class", "badge text-bg-warning me-2 fs-6");
                }
            });
            //ກົດຖອກເງິນ
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
                        <label for="txtRepay" class="form-label text-start w-100">ບໍລິສັດຕິດລົບ</label>
                            <input type="text" class="form-control" placeholder="ບໍລິສັດຕິດລົບ" name="repay" value="${FMoney(RepayValue)}" id="txtRepay">
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
                const txtRepay = $("#txtRepay");

                txtRepay.on("keyup", () => {
                    const repay = Number(txtRepay.val());
                    const money = Calculator(financial);
                    let calAmount = Math.abs(money.calAmount);
                    const total = calAmount - repay;
                });

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
                    const moneys = FormMoney(formdata);

                    if (EmptyMoney(formdata)) {
                        messageAlert("ກະລຸນາປ້ອນຈຳນວນເງິນ.....!");
                    } else {
                        if (Money.calAmount < 0) {
                            //ຖອກແບບຕິດລົບ
                            const repayMoney = Math.abs(Money.calAmount);
                            if (repayMoney == moneys.repay) {
                                createPaymentlist(index, list, financial, formdata, 0);
                            } else {
                                messageAlert("ກະລຸນາຖອກເງິນໃຫ້ພໍດີກັບວົງເງິນ");
                            }
                        } else {
                            // ຖອກເງິນປົກກະຕິ
                            const sum = moneys.cash + moneys.transfer + moneys.repay + moneys.other;
                            if (sum > 0) {
                                createPaymentlist(index, list, financial, formdata, lastMoney);
                            } else {
                                messageAlert("ກະລຸນາຖອກເງິນໃຫ້ພໍດີກັບວົງເງິນ");
                            }
                        }
                    }
                });

            })
        });
    });

    const createPaymentlist = (index, list, financial, formdata, lastPay) => {
        list.attr("class", "d-none");
        PaymentData.push({
            "el": list,
            "data": financial,
            "formdata": formdata,
            "lastPay": lastPay
        });
        showPaymentData();
        List_Disabled(index);
        Swal.close();
    }


    const showPaymentData = () => {
        $("#paymentData").html(``);
        let amount = 0;
        PaymentData.forEach((payment, index) => {
            const financial = payment.data;
            const moneys = FormMoney(payment.formdata);
            const comment = payment.formdata[4];

            const btnDelete = $(`<button class="btn btn-sm btn-danger"><i class='bx bxs-trash'></i></button>`);
            const rowDel = $(`<td class="text-center"></td>`);
            const row = $(`<tr>
            <td class="text-center">${index+1}</td>
            <td class="text-end">${FMoney(moneys.cash)}</td>
            <td class="text-end">${FMoney(moneys.transfer)}</td>
            <td>${FMoney(moneys.repay)}</td>
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
            amount += moneys.cash + moneys.transfer + moneys.other + moneys.repay;
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

        //ບັນທຶກການຖອກເງິນ
        $.post(`./api/PaymentAPI.php?api=create`, data, (respayment) => {
            if (respayment.state) {
                //ບັນທຶກລາຍການຖອກເງິນ
                const savePromises = PaymentData.map(payment => SaveList(payment, respayment));
                Promise.all(savePromises)
                    .then((ms) => {
                        //ການບັນທືກ
                        AlertSave("ບັນທຶກການຖອກເງິນສຳເລັດ", "success", () => {
                            window.open(`./database/PrintTricket.php?paymentid=${respayment.data}&userprint=<?= $userName ?>`, "_blank");
                        });

                    }).catch((err) => {
                        //ການບັນທືກ
                        AlertSave("ບັນທຶກການຖອກເງິນຜິດພາດ", "error", () => Swal.close());
                    });
            }
        });
    }

    const SaveList = (payment, respayment) => {
        const Financial = payment.data;
        const lastPay = payment.lastPay;
        const moneys = FormMoney(payment.formdata);
        const calculator = Calculator(Financial);
        const formData = {
            "paymentID": respayment.data,
            "FinancialID": Financial.FinancialID,
            "cash": moneys.cash,
            "transfer": moneys.transfer,
            "repay": moneys.repay,
            "etc": moneys.other,
            "comment": payment.formdata[4].value
        };
        return new Promise((resolve, reject) => {
            $.post(`./api/PaymentlistAPI.php?api=create`, formData, (res) => {
                isSave = res.state;
                if (res.state) {
                    const total = calculator.calAmount - lastPay;
                    //ກວດສອບສະເພາະຖອກເງິນປົກກະຕິບໍ່ຕິດລົບ
                    if (total > 0) {
                        const isPayfull = moneys.sum >= total;
                        if (isPayfull) {
                            updateFinancialState(Financial.FinancialID);
                        }
                    } else {
                        updateFinancialState(Financial.FinancialID);
                    }
                    resolve(res);
                } else {
                    reject(new Error(res));
                }
            });
        });
    }

    const updateFinancialState = (financialID) => {
        const api = `./api/FinancialAPI.php?api=getUpdateState`;
        const data = {
            "FinancialID": financialID
        };
        $.post(api, data, (res) => {
            console.log(res);
        });
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
            Award: Award,
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

    // ກວດສອບຂໍ້ມູນ
    const EmptyMoney = (formdata) => {
        return formdata[0].value == "" && formdata[1].value == "" && formdata[2].value == "" && formdata[3].value == "";
    }

    //ຂໍ້ມູນຟອມ
    const FormMoney = (formdata) => {
        const cash = UnMoney(formdata[0].value);
        const transfer = UnMoney(formdata[1].value);
        const repay = UnMoney(formdata[2].value);
        const other = UnMoney(formdata[3].value);
        const sum = cash + transfer + repay + other;

        return {
            cash: cash,
            transfer: transfer,
            other: other,
            repay: repay,
            sum: sum,
        }
    }

    //ສະແດງຂໍ້ຄວາມແຈ້ງເຕືອນ
    const messageAlert = (text) => {
        $("#mswarning").text(text);
        const hidems = setTimeout(() => {
            $("#mswarning").text("");
        }, 4000);
    }

    //Aelrt
    const AlertSave = (message, icon, callback) => {
        Swal.fire({
            title: "ບັນທຶກ",
            text: message,
            icon: icon
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }
</script>