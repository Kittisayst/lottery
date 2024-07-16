<?php
if (isset($_COOKIE['lot'])) {
    if ($_COOKIE['lot'] == "0") {
        require_once "SalePDFLaolot.php";
    } else {
        require_once "SalePDF.php";
    }
} else {
    echo "Lot Selected";
}