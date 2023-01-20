<?php


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


function InsertQuery($query){
    list($l, $d) = explode("SET", $query);
    $d = str_replace("=","=>", $d);
    eval("\$r = [{$d}];");
    return $r;
}

function UpdateQuery($query){
    list($l, $d) = explode("SET", $query);
    list($d, $l) = explode("WHERE", $d);
    $d = str_replace("=","=>", $d);
    eval("\$r = [{$d}];");
    return $r;

}

function ListaLogs($tabela, $registro){
    $Query = [];
    $query = "select a.*, b.nome from sis_logs a left join usuarios b on a.usuario=b.codigo where a.tabela = '{$tabela}' and a.registro = '{$registro}' order by a.codigo asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

        switch($d->operacao){

            case 'INSERT':{
                $Query[] = [$d->data, $d->operacao, $d->nome, InsertQuery($d->query)];
                break;
            }
            case 'UPDATE':{
                $Query[] = [$d->data, $d->operacao, $d->nome, UpdateQuery($d->query)];
                break;
            }

        }

    }
    return $Query;
}

function sis_logs($tabela, $codigo, $query, $operacao = null)
{
    $usuario = $_SESSION['usuario']['codigo'];
    $operacao = $operacao ?: strtoupper(trim(explode(' ', $query)[0]));
    $query = Addslashes($query);
    $data = date("Y-m-d H:i:s");

    $query_log = "INSERT INTO sis_logs "
        . "SET usuario = '{$usuario}', registro = '{$codigo}', operacao = '{$operacao}', query = '{$query}', "
        . "tabela = '{$tabela}', data = '{$data}'";

    mysqli_query($con, $query_log);
}

function exclusao($tabela, $codigo, $fisica = false)
{
    if ($fisica) {
        $query = "DELETE FROM {$tabela} WHERE codigo = '{$codigo}'";
    } else {
        $query = "UPDATE {$tabela} SET deletado = '1' WHERE codigo = '{$codigo}'";
    }

    if (mysqli_query($con, $query)) {
        sis_logs($codigo, $query, $tabela, 'DELETE');
        return true;
    } else {
        return false;
    }
}

const DATA = 'd/m/Y';
const DATA_HMS = 'd/m/Y H:i:s';
const DATA_HM = 'd/m/Y H:i';
const HORA_MINUTO = 'H:i';

function formata_datahora($datahora, $formato = null)
{
    if (!$formato) $formato = 'd/m/Y H:i:s';

    if ($datahora == 0) return '(Não definido)';

    return date($formato, strtotime($datahora));
}

function Sts($st)
{
    $opc = [
        'Tramitação' => 'tramitacao',
        'Retorno' => 'retorno',
        'Concluído' => 'concluido',
    ];

    if($opc[$st]){
        return $opc[$st];
    }else{
        return false;
    }

}

//////////////////////////////////////////////////////////////////////////////////////////

    function dataBr($dt){
        list($d, $h) = explode(" ",$dt);
        list($y,$m,$d) = explode("-",$d);
        $data = false;
        if($y && $m && $d){
            $data = "{$d}/{$m}/$y".(($h)?" {$h}":false);
        }
        return $data;
    }

    function dataMysql($dt){
        list($d, $h) = explode(" ",$dt);
        list($d,$m,$y) = explode("/",$d);
        $data = false;
        if($y && $m && $d){
            $data = "{$y}-{$m}-$d".(($h)?" {$h}":false);
        }
        return $data;
    }

    function montaCheckbox($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = json_decode($v['dados']);
        $exibir = $v['exibir'];
        $destino = $v['campo_destino'];
        // $lista[] = print_r($dados, true);
        $lista[] = '<div class="mb-3"><label for="'.$campo.'"><b>'.$rotulo.'</b></label></div>';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <div class="mb-3 form-check">
            <input
                    type="checkbox"
                    name="'.$campo.'[]"
                    value="'.$vetor[$i].'"
                    class="form-check-input"
                    id="'.$campo.$i.'"
                    '.((@in_array($vetor[$i],$dados))?'checked':false).'
                    '.(($exibir[$vetor[$i]])?' exibir="'.$destino.'" ':' ocultar="'.$destino.'"').'
            >
            <label class="form-check-label" for="'.$campo.$i.'">'.$vetor[$i].'</label>
            </div>';
        }

        if($lista){
            return implode(" ",$lista);
        }
    }

    function montaRadio($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = $v['dados'];
        $exibir = $v['exibir'];
        $destino = $v['campo_destino'];

        $lista[] = '<div class="mb-3"><label for="'.$campo.'"><b>'.$rotulo.'</b></label></div>';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <div class="mb-3 form-check">
            <input
                    type="radio"
                    name="'.$campo.'"
                    value="'.$vetor[$i].'"
                    class="form-check-input"
                    id="'.$campo.$i.'"
                    '.(($vetor[$i] == $dados)?'checked':false).'
                    '.(($exibir[$vetor[$i]])?' exibir="'.$destino.'" ':' ocultar="'.$destino.'"').'
            >
            <label class="form-check-label" for="'.$campo.$i.'">'.$vetor[$i].'</label>
            </div>';
        }
        if($lista){
            return implode(" ",$lista);
        }
    }



    function montaCheckboxFiltro($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = $v['dados'];
        $exibir = $v['exibir'];
        $destino = $v['campo_destino'];
        // $lista[] = print_r($dados, true);
        $lista[] = '<div class="mb-3"><label for="'.$campo.'"><b>'.$rotulo.'</b></label></div>';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <div class="mb-3 form-check">
            <input
                    type="checkbox"
                    name="'.$campo.'[]"
                    value="'.$vetor[$i].'"
                    class="form-check-input"
                    id="'.$campo.$i.'"
                    '.((@in_array($vetor[$i],$dados))?'checked':false).'
                    '.(($exibir[$vetor[$i]])?' exibir="'.$destino.'" ':' ocultar="'.$destino.'"').'
            >
            <label class="form-check-label" for="'.$campo.$i.'">'.$vetor[$i].'</label>
            </div>';
        }

        if($lista){
            return implode(" ",$lista);
        }
    }



    function montaOpcPrint($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = json_decode($v['dados']);
        // $lista[] = print_r($dados, true);
        $lista[] = '<div class="mt-3" style="width:100%; float:none;"><b>'.$rotulo.'</b></div><div style="width:100%; float:none;">';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <span margin-left:15px;">
            <i class="fa-solid fa-square" style="color:#ccc"></i> '.$vetor[$i].'</span>';
        }
        $lista[] = '</div>';
        if($lista){
            return implode(" ",$lista);
        }
    }


    function array_multisum($arr){
        $sum = array_sum($arr);
        foreach($arr as $child) {
            $sum += is_array($child) ? array_multisum($child) : 0;
        }
        return $sum;
    }


    $caminho_vendor = getUrl() . "lib/vendor";

date_default_timezone_set('America/Manaus');

if ($_SESSION['usuario']) {

    $query = "SELECT * FROM usuarios WHERE codigo = '{$_SESSION['usuario']['codigo']}'";
    $result = mysqli_query($con, $query);
    $_SESSION['usuario'] = mysqli_fetch_array($result);

    $ConfP = $_SESSION['usuario'];
    $ConfP = $ConfP['permissoes'];
    $ConfP = explode(",", $ConfP);
    for ($i = 0; $i < count($ConfP); $i++) {
        $ConfPermissoes[trim($ConfP[$i])] = trim($ConfP[$i]);
    }
}
