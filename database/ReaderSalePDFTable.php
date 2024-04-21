<?php

use function PHPSTORM_META\map;

require_once ("./database/connectDB.php");
$connnect = new connectDB();
$db = $connnect->getConnection();
$getLimit = $_GET['limit'] ?? 100;
$limit = (int) $getLimit;
$pagination = $_GET['pagination'] ?? 1;
$page = (int) $pagination;
$sql = "SELECT JSON_EXTRACT(pdfData,'$') AS result FROM tb_salepdf WHERE salePDFID =?";
$data = [$_GET['id']];
if (isset($_GET['unitID'])) {
    $sql = "SELECT JSON_ARRAYAGG(json_obj) AS result
  FROM (
      SELECT json_obj
      FROM tb_salepdf,
           JSON_TABLE(pdfData, '$[*]' COLUMNS (
               json_obj JSON PATH '$'
           )) AS j
      WHERE JSON_UNQUOTE(JSON_EXTRACT(json_obj, '$.machineCode')) IN (SELECT tb_machine.machineCode FROM tb_machine WHERE tb_machine.UnitID=?)
      AND tb_salepdf.salePDFID=?
  ) AS filtered_data";
    $data = [$_GET['unitID'], $_GET['id']];
}
$stmt = $db->prepare($sql);
$stmt->execute($data);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data = $result[0]['result'];
$arrdata = json_decode($data, true);

//ກວດສອບວ່າງເປົ່າ
if (!empty($arrdata)) {
    $index = $page <= 1 ? 0 : $limit * $page;
    $html = "";
    $sumSale = 0;
    $sumAward = 0;
    $sumPrice = 0;
    $sumAmount = 0;

    //ກວດສອບການຄົນຫາຕາມໜ່ວຍ
    if (isset($_GET['unitID'])) {
        //ແຍກຂໍ້ມູນໜ່ວຍ
        $createjson = array_map(function ($item) {
            return json_decode($item, true);
        }, $arrdata);

        if (count($createjson) <= $limit) {
            $arrPagination = $createjson;
        } else {
            $groupedArray = array_chunk($createjson, $limit);
            $arrPagination = $groupedArray[$page];
        }
    } else {
        if (count($arrdata) <= $limit) {
            $arrPagination = $arrdata;
        } else {
            $groupedArray = array_chunk($arrdata, $limit);
            $arrPagination = $groupedArray[$page];
        }
    }

    foreach ($arrPagination as $lot) {
        $index++;
        $code = $lot['machineCode'];
        $sale = $lot['Sales'];
        $award = $lot['Award'];
        $percent = $lot['Percentage'];
        $price = $lot['Price'];
        $amount = $lot['Amount'];
        //ຈຳນວນການສະແດງ
        echo "
            <tr class='text-end'>
                <td class='text-center'>$index</td>
                <td class='text-center'>$code</td>
                <td>$sale</td>
                <td>$award</td>
                <td class='text-center col-1'>$percent</td>
                <td class='col-1'>$price</td>
                <td>$amount</td>
            </tr>";
        $sum = sumMoney($lot);
        $sumSale += $sum['Sales'];
        $sumAward += $sum['Award'];
        $sumPrice += $sum['Price'];
        $sumAmount += $sum['Amount'];
    }
    //ສະແດງລວມເງິນ
    echo "<tr class='text-end'>
            <td colspan='2' class='text-center'>ລ່ວມທັງໝົດ</td>
            <td>" . number_format($sumSale) . "</td>
            <td>" . number_format($sumAward) . "</td>
            <td class='text-center'>-</td>
            <td>" . number_format($sumPrice) . "</td>
            <td>" . number_format($sumAmount) . "</td>
        </tr>";
    //ສະແດງປຸ່ມດາວໂຫຼດ
    showButtons();
    createPagination(count($arrdata));
    //ສະແດງຄ່າວ່າງເປົ່າ
} else {
    echo "<tr class='text-center'><td colspan='7'>...........ບໍ່ພົວຂໍ້ມູນທີ່ກົງກັນ...............</td></tr>";
    echo "<script>$('#btnshow').prop('disabled', false);</script>";
}

function sumMoney($lot)
{
    $sale = $lot['Sales'];
    $award = $lot['Award'];
    $price = $lot['Price'];
    $amount = $lot['Amount'];
    return ["Sales" => toInt($sale), "Award" => toInt($award), "Price" => toInt($price), "Amount" => toInt($amount)];
}

function toInt($php_string)
{
    // Remove commas from the string
    $number_without_commas = str_replace(",", "", $php_string);

    // Convert the string to an integer
    return (int) $number_without_commas;
}

function showButtons()
{
    echo "
    <script>
            var rowCount = $('#tableData tr').length;
            $('#btnsavepdf').prop('disabled', rowCount <= 1);
            $('#btnsaveExcel').prop('disabled', rowCount <= 1);
            $('#btnshow').prop('disabled', rowCount <= 1);
            $('#btnSave').prop('disabled', rowCount <= 1);
    </script>";
}

$htmlPagination = "";
function getPagination()
{
    global $htmlPagination;
    return $htmlPagination;
}
function createPagination($dataSize)
{
    global $htmlPagination;
    $limit = $_GET['limit'] ?? 100;
    $paginationLength = (int) ($dataSize / $limit);
    $htmlPagination = Pagination($paginationLength);
    // var_dump($dataSize, $limit, $paginationLength);
    // $htmlPagination = "<ul class='pagination'>";
    // for ($i = 0; $i <= $paginationLength; $i++) {
    //     $htmlPagination .= "<li class='page-item'><button class='page-link'>$i</button></li>";
    // }
    // $htmlPagination .= "</ul>";
}

function Pagination(int $totalPages): string
{
    global $htmlPagination;
    $page = $_GET['pagination'] ?? 0;
    $currentPage = (int) $page;
    $maxButtons = 20;
    $htmlPagination = "<ul class='pagination'>";
    $lotID = $_GET['id'];

    // Calculate start and end page based on current page
    $startPage = max(1, $currentPage - floor($maxButtons / 2));
    $endPage = min($totalPages, $startPage + $maxButtons - 1);

    // Adjust start page if end page exceeds total pages
    $startPage = max(1, $endPage - $maxButtons + 1);

    // Add previous button if not on the first page
    if ($currentPage > 1) {
        $Previous = $currentPage - 1;
        $htmlPagination .= "<li class='page-item'><a class='page-link' href='?page=scanpayment&id=$lotID&pagination=1'>&laquo;</a></li>";
        $htmlPagination .= "<li class='page-item'><a class='page-link' href='?page=scanpayment&id=$lotID&pagination=$Previous'>ກ່ອນໜ້າ</a></li>";
    }

    // Generate page buttons
    for ($i = $startPage; $i <= $endPage; $i++) {
        $activeClass = ($i === $currentPage) ? "active" : "";
        $htmlPagination .= "<li class='page-item $activeClass'><a class='page-link' href='?page=scanpayment&id=$lotID&pagination=$i'>$i</a></li>";
    }

    // Add next button if not on the last page
    if ($currentPage < $totalPages) {
        $next = $currentPage + 1;
        $htmlPagination .= "<li class='page-item'><a class='page-link' href='?page=scanpayment&id=$lotID&pagination=$next'>ຕໍ່ໄປ</a></li>";
        $htmlPagination .= "<li class='page-item'><a class='page-link' href='?page=scanpayment&id=$lotID&pagination=$totalPages'>&raquo;</a></li>";
    }

    $htmlPagination .= "</ul>";
    return $htmlPagination;
}


