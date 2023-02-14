<?php
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '10.0.0.115') {
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'politica');
} else {
    define('DB_HOST', '3.93.20.163');
    define('DB_USERNAME', 'politica');
    define('DB_PASSWORD', 'P0l!t!c@');
    define('DB_DATABASE', 'politica');
}


$con = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8', $con);
mysql_select_db(DB_DATABASE, $con);

if (!$con) {
    die('Não foi possível conectar: ' . mysql_error());
}

#mysql_close($con);