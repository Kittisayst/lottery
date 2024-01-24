<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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
include("../database/paymentPrinterDetail.php");
$printer = getTicket();
//ການຖອກເງິນ
$cash = (int)$printer['Cash'];
$transfer = (int)$printer['Transfer'];
$etc = (int)$printer['Etc'];
$price = $cash + $transfer + $etc;
//ການຂາຍ
$sales = (int)$printer['Sales'];
$percentate = (int)$printer['Percentage'];
$award = (int)$printer['Award'];
$calpercentage = ($sales * $percentate) / 100;
$amount = $sales - $calpercentage - $award;
$total = $amount - $price;



function FMoney($number)
{
    return number_format($number);
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
            <span>ສາຂາແຂວງ: <?= $printer['pname'] ?></span>
            <span>ເລກໜ່ວຍ: <?= $printer['unitID'] ?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span>ໜ່ວຍຂາຍ: <?= $printer['unitName'] ?></span>
            <span>ເລກທີ: <?= $printer['paymentNo'] ?>-<?= $printer['paymentID'] ?></span>
        </div>
        <p>ຈຳນວນເງິນຕ້ອງຖອກ: <?= FMoney($amount) ?> ກີບ</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col" rowspan="2" class="align-middle text-center" style="width: 15px;">ລຳດັບ</th>
                    <th scope="col" colspan="3" class="text-center">ລາຍການຈ່າຍ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ເງິນສົດ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ເງິນໂອນ</th>
                    <th scope="col" rowspan="2" class="align-middle text-center">ລາງວັນ</th>
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
                <tr>
                    <td scope="row" class="text-center">1</td>
                    <td class="text-center"><?= $printer['lotteryNo'] ?></td>
                    <td class="text-center"><?= date_format(date_create($printer['lotDate']), "d/m/Y") ?></td>
                    <td class="text-center"><?= $printer['lotteryCorrect'] ?></td>
                    <td class="text-end"><?= FMoney($cash) ?></td>
                    <td class="text-end"><?= FMoney($transfer) ?></td>
                    <td class="text-end"><?= FMoney($award) ?></td>
                    <td class="text-end"><?= FMoney($etc) ?></td>
                    <td class="text-end"><?= FMoney($price) ?></td>
                </tr>
            </tbody>
        </table>
        <div>
            <p class="d-flex justify-content-between"><span>ວັນທີ <?= date("d/m/Y") ?></span><span>ໜີ້ຄ້າງ: <?= FMoney($total) ?> ກີບ</span></p>
        </div>
        <div class="d-flex justify-content-between">
            <p>ເຊັນຜູ້ຈ່າຍ</p>
            <p class="d-flex flex-column justify-content-center align-items-center">
                <span class="mb-5">ເຊັນຜູ້ຮັບ</span>
                <span class="mt-3"><?= $_GET['userptring'] ?></span>
            </p>
        </div>
    </div>
</body>

</html>