<?php
    session_start();

    // include("connect_local.php");
    include("/appinc/connect.php");
    $con = AppConnect('politica');

    // $_SESSION = [];

    // include("/appinc/connect.php");
    include("fn.php");

    $md5 = md5(date("YmdHis"));

    $localPainel = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/painel/";

    // $ConfPermissoes = [
    //     'Certidão de Nascimento - Visualizar',
    //     'Registro Geral - Visualizar',
    //     'CRAS - Visualizar',
    //     'CR - Visualizar',
    //     'Psicologia - Visualizar',
    //     'Odontologia - Visualizar',
    //     'Jurídico - Visualizar',
    // ];
