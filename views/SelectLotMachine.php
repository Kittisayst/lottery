<?php
if (isset($_COOKIE['lot'])) {
    $lot = $_COOKIE['lot'];
    if ($lot == "0") {
        require_once "./views/lotmachine.php";
    } else {
        require_once "./views/lotmachineLotlink.php";
    }
}
