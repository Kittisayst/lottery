<?php
if (isset($_COOKIE['lot'])) {
    if ($_COOKIE['lot'] == "0") {
        require_once "SalePDF.php";
    } else {
        require_once "SalePDFLotLink.php";
    }
} else {
    echo "Lot Selected";
}