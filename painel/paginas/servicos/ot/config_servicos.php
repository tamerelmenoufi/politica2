<?php
include("{$_SERVER['DOCUMENT_ROOT']}/politica/painel/lib/includes.php");

$urlServicos = 'paginas/servicos/ot';

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
        'indeferido' => 'Indeferido',
        'concluido' => 'Concluído',
    ];
}

function getSituacaoOptions($situacao)
{
    $list = getSituacao();
    return $list[$situacao];
}
