<?php

    include "config_beneficiados.php";

    $query = "select * from assessores where codigo = '{$_POST['assessor']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    echo $d->codigo;