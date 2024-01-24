<div class="container content">
    <h1 class="text-center">ໜ້າການຖອກເງິນ</h1>
    <form id="formsearch">
        <div class="d-flex justify-content-end gap-4 mb-3">
            <div class="">
                <select class="form-select" aria-label="Default select example" name="provinceID" id="cbprovince" required>
                    <?php
                    include("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div class="">
                <select class="form-select" aria-label="Default select example" name="paymentID" id="cbpaymentNo" required>
                    <?php
                    include("./database/LotteryOption.php");
                    ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit" name="search">ສະແດງ</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ງວດທີ</th>
                <th scope="col">ແຂວງ</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ເງີນຕ້ອງຖອກ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <tr>
                <td colspan="5" class="text-center">---- ຄົ້ນຫາຂໍ້ມູນການຖອກເງິນ ----</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    const formsearch = $('#formsearch');
    const tbdata = $('#tbdata');
    formsearch.on("submit", (e) => {
        e.preventDefault();
        const formData = formsearch.serialize();
        $.post("./api/PaymentAPI.php?api=search", formData, (res) => {
            if (res.state) {
                const payments = res.data;
                tbdata.html("");
                payments.forEach((payment, index) => {
                    tbdata.append(CreatePaymentRow(payment, index));
                });
            } else {
                tbdata.html(`<tr><td colspan="5" class="text-center">---- ບໍ່ພົບຂໍ້ມູນການຖອກເງິນ ----</td></tr>`);
            }
        });
    });

    const formatMoney = (money) => {
        return new Intl.NumberFormat('en-US').format(String(money));
    }

    function CreatePaymentRow(payment, index) {
        const Sales = Number(payment['Sales']);
        const Percentage = Number(payment['Percentage']);
        const Award = Number(payment['Award']);
        const calPercent = (Sales * Percentage) / 100;
        const Amount = Sales - calPercent - Award;
        const formatSales = new Intl.NumberFormat('en-US').format(Sales);
        const formattedAmount = new Intl.NumberFormat('en-US').format(Amount);
        const formatCalpercent = new Intl.NumberFormat('en-US').format(calPercent);
        const formatAward = new Intl.NumberFormat('en-US').format(Award);
        const tr = $(`<tr id="col${payment['FinancialID']}"></tr>`);
        let sum = 0;
        $(() => {
            $.get(`./api/PaymentAPI.php?api=isPayment&fid=${payment['FinancialID']}`)
                .done((res) => {
                    res.data.forEach(pay => {
                        const getcash = Number(pay['Cash']);
                        const gettrnsfer = Number(pay['Transfer']);
                        const getetc = Number(pay['Etc']);
                        sum += getcash + gettrnsfer + getetc;
                    });
                    // If you want to update the HTML content with the response, you can do something like this:
                    $(`#row${payment['FinancialID']} .badge`).html(`<span>${formatMoney(Amount-sum)}</span>`);
                })
                .fail((error) => {
                    console.error("Error:", error);
                });
        });
        tr.html(`
                <td>${index+1}</td>
                <td>${payment['lotteryNo']}</td>
                <td>${payment['pname']}</td>
                <td>${payment['unitName']}</td>
                <td id="row${payment['FinancialID']}" class="text-end col-2">
                <spn class="badge text-bg-danger">
                    NaN
                </spn>
                </td>
            `);
        tr.click(() => {
            paymentAlert(payment, Amount, formatSales, formatMoney(Amount-sum), formatCalpercent, formatAward);
        });
        return tr;
    }

    const paymentAlert = (payment, Amount, formatSales, formattedAmount, formatCalpercent, formatAward) => {
        const unitText = `
                <span>ງວດທີ: ${payment['lotteryNo']}</span>
                <span>ຍອດຂາຍ: ${formatSales} ₭</span>
                <span>ເປີເຊັນ: ${payment['Percentage']}% ເປັນເງິນ: ${formatCalpercent} ₭</span>
                <span>ລາງວັນ: ${formatAward} ₭</span>
                <span>ເງິນທີ່ຕ້ອງຖອກ: ${formattedAmount} ₭</span>
                `;
        Swal.fire({
            title: payment['unitName'],
            html: `
                        <form class="px-2" id="frmlottery">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="FinancialID" value="${payment['FinancialID']}" hidden>
                                <input type="text" class="form-control" name="UserID" value="<?php print_r($_SESSION['user'][0]['userID']) ?>" hidden>
                                <div class="d-flex flex-column align-items-start">
                                    ${unitText}
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex gap-2 w-100 mb-3">
                                <div class="w-50">
                                    <label for="" class="form-label w-100 text-start">ເລກທີ</label>
                                    <input type="text" class="form-control" placeholder="ເລກທີ" id="txtpaymentNoShow" value="<?php include("./database/getPaymentNo.php"); ?>" name="payNo" disabled>
                                    <input type="text" class="form-control" placeholder="ເລກທີ" id="txtpaymentNo" name="paymentNo" value="<?php include("./database/getPaymentNo.php"); ?>"  hidden>
                                </div>
                                <div class="w-auto">
                                    <label for="" class="form-label w-100 text-start">ວັນທີ</label>
                                    <input type="text" class="form-control" placeholder="ວັນທີ" id="txtSaveDate" name="SaveDate" value="<?= date("d/m/Y") ?>" disabled>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="badge text-bg-light">${formattedAmount}  ກີບ</span>
                                <span class="fs-4 text-center text-danger" id="showMoney">ຍັງຄ້າງ: ${formattedAmount} ກີບ</span>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ເງິນສົດ</label>
                                <input type="text" class="form-control" placeholder="ເງິນສົດ" id="txtcash" name="Cash" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ເງິນໂອນ</label>
                                <input type="text" class="form-control" placeholder="ເງິນໂອນ" id="txttransfer" name="Transfer" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ອື່ນໆ</label>
                                <input type="text" class="form-control" placeholder="ອື່ນໆ" id="txtetc" name="Etc">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label w-100 text-start">ໝາຍເຫດ</label>
                                <textarea id="txtcoment" name="Comment" rows="4" cols="1" class="form-control" placeholder="ໝາຍເຫດ"></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">ບັນທຶກການຖອກເງິນ</button>
                            </div>
                        </form>
                        `,
            showConfirmButton: false
        });
        alertEvent(Amount);
        formEvent();
    }

    const alertEvent = (Amount) => {
        const txtpaymentNoShow = $("txtpaymentNoShow");
        const txtpaymentNo = $("#txtpaymentNo");
        const txtCash = $("#txtcash");
        const txtTransfer = $("#txttransfer");
        const txtEtc = $("#txtetc");
        const txtComent = $("#txtcoment");
        const showMoney = $("#showMoney");

        txtCash.on("keyup", () => {
            const str = txtCash.val();
            const formatval = str.replace(/[^0-9.-]+/g, "");
            const value = new Intl.NumberFormat('en-US').format(formatval);
            calculatorAmout();
            txtCash.val(value);
        });

        txtTransfer.on("keyup", () => {
            const str = txtTransfer.val();
            const formatval = str.replace(/[^0-9.-]+/g, "");
            const value = new Intl.NumberFormat('en-US').format(formatval);
            calculatorAmout();
            txtTransfer.val(value);
        });

        txtEtc.on("keyup", () => {
            const str = txtEtc.val();
            const formatval = str.replace(/[^0-9.-]+/g, "");
            const value = new Intl.NumberFormat('en-US').format(formatval);
            calculatorAmout();
            txtEtc.val(value);
        });

        const calculatorAmout = () => {
            const cashval = format(txtCash.val());
            const transferval = format(txtTransfer.val());
            const etcval = format(txtEtc.val());
            const minus = cashval + transferval + etcval;
            const total = Amount - minus;
            const formatTotal = new Intl.NumberFormat('en-US').format(total);
            showMoney.text("ຍັງຄ້າງ: " + formatTotal + " ກີບ");
        }

        const format = (str) => {
            const formatval = str.replace(/[^0-9.-]+/g, "");
            return Number(formatval);
        }
    }

    const formEvent = () => {
        const frmlottery = $("#frmlottery");
        frmlottery.on("submit", (e) => {
            e.preventDefault();
            const formData = frmlottery.serialize();
            <?php
            $userPrint = $_SESSION['user'][0]['UserName'];
            ?>
            $.post("./api/PaymentAPI.php?api=create", formData, (res) => {
                if (res.state) {
                    Printing(res.data);
                }
            });

        })

    }

    const Printing = (id) => {
        const newTab = window.open(`/lottery/database/PrintTricket.php?paymentid=${id}&userptring=<?= $userPrint ?>`, '_blank');
        // Wait for a short delay to allow the content to load
        setTimeout(function() {
            // Print the content of the new tab
            newTab.print();
        }, 2000); // Adjust the delay as needed
    }
</script>