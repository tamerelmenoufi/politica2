<?php
include("{$_SERVER['DOCUMENT_ROOT']}/politica/painel/lib/includes.php");

$urlLogs = 'paginas/servicos/logs';

function getEsfera()
{
    return [
        'Municipal', 'Estadual'
    ];
}

function getSituacao()
{
    return [
        'tramitacao' => 'Tramitação',
        'retorno' => 'Retorno',
        'concluido' => 'Concluído',
    ];
}

function getSituacaoOptions($situacao)
{
    $list = getSituacao();
    return $list[$situacao];
}
