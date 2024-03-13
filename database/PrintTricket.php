<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printer</title>
    <link rel="shortcut icon" href="../public/lotteryIcon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <style>
        /* Styles for printing */
        body {
            font-family: "Phetsarath OT", sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            /* Add more styles as needed */
        }

        .content {
            /* Styles for the content to be printed */
            color: black;
            /* Add more styles as needed */
        }

        /* Hide elements not to be printed */
        .no-print {
            display: none;
        }

        @media print {
            @page {
                size: A5;
                margin: 2.5mm;
                /* Adjust margins as needed */
            }

            body {
                margin: 0;
                font-size: 8pt;
                padding: 10mm;
                /* Add padding to prevent content from getting too close to the edges */
            }
        }
    </style>
</head>

<?php
$paymentid = $_GET['paymentid'];
require_once("../database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$sql = 'SELECT * FROM tb_paymentlist
INNER JOIN tb_payment ON tb_paymentlist.paymentID = tb_payment.paymentID
INNER JOIN tb_financail ON tb_paymentlist.FinancialID = tb_financail.FinancialID
INNER JOIN tb_lottery ON tb_financail.lotteryID = tb_lottery.lotteryID
INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
INNER JOIN tb_province ON tb_unit.provinceID = tb_province.pid
WHERE tb_paymentlist.paymentID=?';
$stmt = $db->prepare($sql);
$stmt->execute([$_GET['paymentid']]);
$result = $stmt->fetchAll();
$printers = $result;
$paymentData =  $printers[0];

function FMoney($number)
{
    return number_format($number);
}

function getList($row)
{
    //ການຖອກເງິນ
    $withdraw = (int)$row['withdrawn'];
    $cash = (int)$row['cash'];
    $transfer = (int)$row['transfer'];
    $repay = (int)$row['repay'];
    $etc = (int)$row['etc'];
    $comment = $row['comment'];
    $price = $cash + $transfer + $etc;
    $SaveDate = $row['SaveDate'];
    //ການຂາຍ
    $sales = (int)$row['Sales'];
    $percentate = (int)$row['Percentage'];
    $award = (int)$row['Award'];
    $lotteryCorrect = $row['lotteryCorrect'];
    $calpercentage = ($sales * $percentate) / 100;
    $amount = $sales - $calpercentage;
    $money = $withdraw == 0 ? $amount : $amount - $award;
    $total = $amount - $price;
    return [
        'sales' => number_format($sales),
        'percentate' => $percentate,
        'award' => number_format($award),
        "calpercentage" => $calpercentage,
        "amount" => $money,
        "total" => number_format($total),
        "cash" => number_format($cash),
        "transfer" => number_format($transfer),
        "repay" => number_format($repay),
        "etc" => number_format($etc == 0 ? $repay : $etc),
        "comment" => $comment,
        "SaveDate" => $SaveDate,
        "lotteryno" => $row['lotteryNo'],
        "lotteryCorrect" => $lotteryCorrect,
        "price" => number_format($price)
    ];
}

?>

<body>
    <div class="d-flex flex-column">
        <p class="text-center">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ<br>ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດທະນະຖາວອນ</p>
        <div class="d-flex my-4">
            <div class="flex-grow-1">
                <img class="" src="../public/lotteryIcon.png" alt="logo" width="50px">
            </div>
            <h5 class="fw-bold align-self-center flex-grow-1 pe-5">ໃບບິນຮັບເງິນ</h5>
        </div>
        <div class="d-flex justify-content-between">
            <span>ສາຂາແຂວງ: <?= $paymentData['pname'] ?></span>
            <span>ເລກໜ່ວຍ: <?= $paymentData['unitID'] ?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span>ໜ່ວຍຂາຍ: <?= $paymentData['unitName'] ?></span>
            <span>ເລກທີ: KF<?= $paymentData['paymentID'] ?></span>
        </div>
        <p>ຈຳນວນເງິນຕ້ອງຖອກ: <span id="Totalmoney"></span> ກີບ</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col" rowspan="2" class="align-middle text-center" style="width: 15px;">ລຳດັບ</th>
                    <th scope="col" colspan="3" class="text-center">ລາຍການຈ່າຍ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ລາງວັນ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ເງິນສົດ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ເງິນໂອນ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ອື່ນໆ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ລວມ</th>
                </tr>
                <tr>
                    <th scope="col" class="text-center">ງວດ</th>
                    <th scope="col" class="text-center">ວັນທີ</th>
                    <th scope="col" class="text-center">ເລກອອກ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($printers as $row) {
                    $list = getList($row);
                    $total += $list['amount'];
                ?>
                    <tr>
                        <td scope="row" class="text-center">1</td>
                        <td class="text-center"><?= $list['lotteryno'] ?></td>
                        <td class="text-center"><?= date("d/m/Y", strtotime($list['SaveDate'])) ?></td>
                        <td class="text-center"><?= $list['lotteryCorrect'] ?></td>
                        <td class="text-end"><?= $list['award'] ?></td>
                        <td class="text-end"><?= $list['cash'] ?></td>
                        <td class="text-end"><?= $list['transfer'] ?></td>
                        <td class="text-end"><?= $list['etc'] ?></td>
                        <td class="text-end"><?= number_format($list['amount']) ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div>
            <p class="d-flex justify-content-between"><span>ວັນທີ <?= date("d/m/Y") ?></span><span>ໜີ້ຄ້າງ: <?= FMoney($total) ?> ກີບ</span></p>
        </div>
        <div class="d-flex justify-content-between">
            <p>ເຊັນຜູ້ຈ່າຍ</p>
            <p class="d-flex flex-column justify-content-center align-items-center">
                <span class="mb-5">ເຊັນຜູ້ຮັບ</span>
                <span class="mt-3">testing</span>
            </p>
        </div>
    </div>
</body>
<script>
    $("#Totalmoney").text("<?= number_format($total) ?>");
</script>

</html>