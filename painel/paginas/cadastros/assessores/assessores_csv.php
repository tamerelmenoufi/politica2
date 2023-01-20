<?php
    include "config_assessores.php";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=assessores.csv');

    $query = "SELECT * FROM assessores WHERE deletado != '1'";
    $result = mysqli_query($con, $query);
        echo "ASSESSOR(A);CPF;RESPONSÁVEL\n";
    while ($d = mysqli_fetch_object($result)):
        echo "{$d->nome};{$d->cpf};{$d->responsavel}\n";
    endwhile;