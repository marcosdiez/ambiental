<?php

require_once("../libs/phpqrcode/qrlib.php");

$text="use the GET parameter [code] to generate a custom QRCode";
if(isset($_GET['code'])) {
    $text = $_GET['code'];
}

QRcode::png($text);