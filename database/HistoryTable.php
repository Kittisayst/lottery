<?php
if (isset($_GET['unitid'])) {
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT * FROM tb_paymentlist
    INNER JOIN tb_financail ON tb_paymentlist.FinancialID = tb_financail.FinancialID
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    WHERE tb_unit.unitID=? GROUP BY paymentID ORDER BY PaylistID DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['unitid']]);
    $result = $stmt->fetchAll();
    $state = $stmt->rowCount() > 0;
    $index = 1;
    if ($state) {
        foreach ($result as $row) {
            $money = getMoney($row['paymentID']);
?>
            <tr>
                <td class="text-center"><?= $index++ ?></td>
                <td class="text-center">KF<?= $row['paymentID'] ?></td>
                <td class="text-center"><?= date("d/m/Y", strtotime($row['SaveDate'])) ?></td>
                <td class="text-end"><?= $money['cash'] ?></td>
                <td class="text-end"><?= $money['transfer'] ?></td>
                <td class="text-end"><?= $money['repay'] ?></td>
                <td class="text-end"><?= $money['etc'] ?></td>
                <td class="text-center" id="<?= $row['paymentID'] ?>">
                    <button class="btn btn-sm btn-info text-secondary-emphasis" onclick="showalert(<?= $row['paymentID'] ?>)"><i class='bx bxs-show'></i></button>
                    <a href="#" class="btn btn-sm btn-primary"><i class='bx bxs-printer'></i></a>
                </td>
            </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>ບໍ່ພົບຂໍ້ມູນການຖອກເງິນ</td></tr>";
    }
    ?>
    <script>
        function showalert(paymentID) {
            Swal.fire({
                title: `ເລກທີບິນ: KF${paymentID}`,
                html: `
                <div class="text-start">
                    <table class="table table-bordered table-hover mt-3">
                        <thead class="table-warning">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">ເງິນສົດ</th>
                                <th scope="col">ເງິນໂອນ</th>
                                <th scope="col">ບໍລິສັດຕິດລົບ</th>
                                <th scope="col">ອື່ນໆ</th>
                                <th scope="col">ໝາຍເຫດ</th>
                            </tr>
                        </thead>
                            <tbody id="tbAlert">

                            </tbody>
                    </table>                    
                    <div class="w-100 text-end">                        
                        <span id="msAmount" class="fs-5 fw-bold"></span>                        
                    </div>                    
                </div>`,
                width: 600,
            });
            $.get(`./api/PaymentlistAPI.php?api=getpaymentlistbyID&paymentID=${paymentID}`, (res) => {
                const payments = res.data;
                const content = $("#tbAlert");
                console.log(payments);
                let sum = 0;
                payments.forEach((payment, index) => {
                    const moneys =  createMoney(payment);
                    sum += moneys.sum;
                    const col = $("<tr></tr>");
                    col.html(`
                    <td>${index+1}</td>
                    <td class="text-end">${moneys.cash}</td>
                    <td class="text-end">${moneys.transfer}</td>
                    <td class="text-end">${moneys.repay}</td>
                    <td class="text-end">${moneys.etc}</td>
                    <td>${payment['comment']}</td>
                    `);
                    content.append(col);
                });
                $("#msAmount").text(`ລວມເງິນທັງໝົດ: ${sum.toLocaleString()} ກີບ`);
            });
        }

        function createMoney(payment) {
            const cash = Number(payment['cash']);
            const transfer = Number(payment['transfer']);
            const repay = Number(payment['repay']);
            const etc = Number(payment['etc']);
            const sum = cash + transfer + repay + etc;
            return {
                cash: cash.toLocaleString(),
                transfer: transfer.toLocaleString(),
                repay: repay.toLocaleString(),
                etc: etc.toLocaleString(),
                sum: sum
            };
        }
    </script>
<?php
}




function getMoney($paymentID)
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT * FROM tb_paymentlist WHERE paymentID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$paymentID]);
    $result = $stmt->fetchAll();
    $cash = 0;
    $transfer = 0;
    $repay = 0;
    $etc = 0;
    $Money = [];

    foreach ($result as $row) {
        $cash += $row['cash'];
        $transfer += $row['transfer'];
        $repay += $row['repay'];
        $etc += $row['etc'];
    }
    $Money = ["cash" => number_format($cash), "transfer" => number_format($transfer), "repay" => number_format($repay), "etc" => number_format($etc)];
    return $Money;
}

?>