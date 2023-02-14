<?php

if (session_id() === "") {
    session_start();
}

function getUrl()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }


    if ($_SERVER['HTTP_HOST'] === 'localhost') return $protocol . "://localhost/dsv/politica/";

    #return $protocol . "://" . $_SERVER['HTTP_HOST'];
    return 'http://politica.mohatron.com/';
}

$caminho_vendor = getUrl() . "lib/vendor";

date_default_timezone_set('America/Manaus');

if ($_SESSION['usuario']) {

    $query = "SELECT * FROM usuarios WHERE codigo = '{$_SESSION['usuario']['codigo']}'";
    $result = mysql_query($query);
    $_SESSION['usuario'] = mysql_fetch_array($result);

    $ConfP = $_SESSION['usuario'];
    $ConfP = $ConfP['permissoes'];
    $ConfP = explode(",", $ConfP);
    for ($i = 0; $i < count($ConfP); $i++) {
        $ConfPermissoes[trim($ConfP[$i])] = trim($ConfP[$i]);
    }
}