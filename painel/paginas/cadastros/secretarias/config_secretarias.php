<?php
include("{$_SERVER['DOCUMENT_ROOT']}/politica/painel/lib/includes.php");

$urlSecretarias = 'paginas/cadastros/secretarias';

function getEsfera()
{
    return [
        'Municipal',
        'Estadual'
    ];
}
