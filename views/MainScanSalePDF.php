<?php
if (isset($_COOKIE['lot'])) {
    if ($_COOKIE['lot']== "0") {
        require_once "ScanPDFSale.php";
    } else {
        require_once "ScanPDFSaleLotlink.php";
    }
} else {
    echo "Not Found Lot";
}