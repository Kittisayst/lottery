<?php
if (isset($_COOKIE['lot'])) {
    $lot = $_COOKIE['lot'];
    if ($lot == "0") {
        require_once "./views/lotmachineLaolot.php";
    } else {
        require_once "./views/lotmachine.php";
    }
}
