<?php
if (isset($_COOKIE['lot'])) {
    if ($_COOKIE['lot']== "0") {
        var_dump($_COOKIE['lot']);
        require_once "ScanPDFSaleLaolot.php";
    } else {
        require_once "ScanPDFSale.php";
    }
} else {
    echo "Not Found Lot";
}