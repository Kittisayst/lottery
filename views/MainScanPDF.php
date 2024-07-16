<?php
if (isset($_COOKIE['lot'])) {
    if ($_COOKIE['lot']== "0") {
        require_once "ScanPDFLaolot.php";
    } else {
        require_once "ScanPDF.php";
    }
} else {
    echo "Not Found Lot";
}