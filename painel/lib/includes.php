<?php
    session_start();

    // include("connect_local.php");
    include("/appinc/connect.php");
    $con = AppConnect('portal');

    // include("/appinc/connect.php");
    include("fn.php");

    $md5 = md5(date("YmdHis"));

    $localPainel = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/painel/";
