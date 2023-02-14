<?php
include "config_servicos.php";

if($_POST['acao'] == 'situacao_log'){
    $query = "select * from servicos where codigo = '{$_POST['codigo']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
    $logs = json_decode($d->situacao_log);
    foreach($logs as $ind => $reg){
        echo "Situação: ".$reg->status."<br>";
        echo "Data: ".$reg->data."<br><hr>";
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;
    $situacao_log = $data['situacao_log'];
    $situacao_log_novo = $data['situacao_log_novo'];

    unset($data['codigo']);
    unset($data['situacao_log']);
    unset($data['situacao_log_novo']);

    foreach ($data as $name => $value) {
        $attr[] = "{$name} = '" . Addslashes($value) . "'";
    }
    if ($situacao_log) {
        if($situacao_log_novo == 'novo'){
            $attr[] = "situacao_log = '[{\"status\":\"{$situacao_log}\", \"data\":\"".date("d/m/Y H:i:s")."\"}]'";
        }else{
            $attr[] = "situacao_log = concat( SUBSTR(situacao_log, 1, LENGTH (situacao_log)-1) ,',{\"status\":\"{$situacao_log}\", \"data\":\"".date("d/m/Y H:i:s")."\"}]')";
        }
    }elseif($situacao_log_novo == 'novo'){
            $attr[] = "situacao_log = '[{\"status\":\"tramitacao\", \"data\":\"".date("d/m/Y H:i:s")."\"}]'";
    }
    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE servicos SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO servicos SET {$attr}";
    }

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('servicos', $codigo, $query);

        echo json_encode([
            'status' => true,
            'msg' => 'Dados salvo com sucesso',
            'codigo' => $codigo,
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'msg' => 'Erro ao salvar',
            'codigo' => $codigo,

        ]);
    }

    exit;
}

$codigo = $_GET['codigo'];

if ($codigo) {
    $query = "SELECT * FROM servicos WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>


<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> Serviços
        </h6>
    </div>
    <div class="card-body">
        <form id="form-servicos">

            <div class="form-group">
                <label for="tipo">
                    Tipo de Serviço <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control mb-2"
                        id="tipo"
                        name="tipo"
                        data-live-search="true"
                        data-none-selected-text="Selecione"
                        required
                >
                    <option value=""></option>
                    <?php
                    $query = "SELECT * FROM servico_tipo ORDER BY tipo";
                    $result = mysqli_query($con, $query);

                    while ($s = mysqli_fetch_object($result)): ?>
                        <option
                            <?= ($codigo and $d->tipo == $s->codigo) ? 'selected' : ''; ?>
                                value="<?= $s->codigo ?>">
                            <?= $s->tipo; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="form-group">
                <label for="beneficiado">
                    Beneficiado <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control mb-2"
                        id="beneficiado"
                        name="beneficiado"
                        data-live-search="true"
                        data-none-selected-text="Selecione"
                        required
                >
                    <option value=""></option>
                    <?php
                    $query = "SELECT * FROM beneficiados ORDER BY nome";
                    $result = mysqli_query($con, $query);

                    while ($b = mysqli_fetch_object($result)): ?>
                        <option
                            <?= ($codigo and $d->beneficiado == $b->codigo) ? 'selected' : ''; ?>
                                value="<?= $b->codigo ?>">
                            <?= $b->nome; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="form-group">
                <label for="contato">
                    Contato <i class="text-danger">*</i>
                </label>
                <input
                        type="text"
                        class="form-control mb-2"
                        id="contato"
                        name="contato"
                        value="<?= $d->contato; ?>"
                        required
                >

            </div>

            <div class="form-group">
                <label for="especialista">
                    Especialista <i class="text-danger">*</i>
                </label>
                <input
                        type="text"
                        class="form-control mb-2"
                        id="especialista"
                        name="especialista"
                        value="<?= $d->especialista; ?>"
                        required
                >

            </div>

            <div class="form-group">
                <label for="assessor">
                    Assessor <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control mb-2"
                        id="assessor"
                        name="assessor"
                        data-live-search="true"
                        data-none-selected-text="Selecione"
                        required
                >
                    <option value=""></option>
                    <?php
                    $query = "SELECT * FROM assessores ORDER BY nome";
                    $result = mysqli_query($con, $query);

                    while ($a = mysqli_fetch_object($result)): ?>
                        <option
                            <?= ($codigo and $d->assessor == $a->codigo) ? 'selected' : ''; ?>
                                value="<?= $a->codigo ?>">
                            <?= $a->nome; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="form-group">
                <label for="local_fonte">
                    Fonte Local <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control mb-2"
                        id="local_fonte"
                        name="local_fonte"
                        data-live-search="true"
                        data-none-selected-text="Selecione"
                        required
                >
                    <option value=""></option>
                    <?php
                    $query = "SELECT * FROM local_fontes ORDER BY descricao";
                    $result = mysqli_query($con, $query);

                    while ($l = mysqli_fetch_object($result)): ?>
                        <option
                            <?= ($codigo and $d->local_fonte == $l->codigo) ? 'selected' : ''; ?>
                                value="<?= $l->codigo ?>">
                            <?= $l->descricao; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="data_agenda">Data de Agenda <i class="text-danger"></i></label>
                        <input
                                type="datetime-local"
                                class="form-control mb-2"
                                id="data_agenda"
                                name="data_agenda"
                                value="<?= $codigo ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($d->data_agenda)) : ''; ?>"
                        >

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="situacao">
                            Situação <i class="text-danger">*</i>
                        </label>
                        <div class="input-group">

                            <select
                                    class="form-control"
                                    id="situacao"
                                    name="situacao"
                                    required
                            >
                                <?php
                                foreach (getSituacao() as $key => $value): ?>
                                    <option
                                        <?= ($codigo and $d->situacao == $key) ? 'selected' : ''; ?>
                                            value="<?= $key; ?>">
                                        <?= $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <span class="input-group-text">
                                Histórico
                            </span>
                            <input type="hidden" id="situacao_log" name="situacao_log" value="" />
                            <input type="hidden" id="situacao_log_novo" name="situacao_log_novo" value="<?=((!$d->situacao_log)?'novo':false)?>" />
                            <button type="button" class="btn btn-secondary" id="ver_logs_situacao" codigo="<?=$d->codigo?>">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
</div>

<script>
    $(function(){ Carregando('none');
        $('#contato').mask('(99) 99999-9999');

        $("#assessor").selectpicker();

        $("#beneficiado").selectpicker();

        $("#tipo").selectpicker();

        $("#local_fonte").selectpicker();

        $('#form-servicos').validate();

        atual = '<?=$d->situacao_log?>';
        if(!atual){
            situacao = $("#situacao").val();
            $("#situacao_log").val(situacao);
        }

        $("#situacao").change(function(){
            atual = '<?=$d->situacao?>';
            situacao = $(this).val();
            if(atual != situacao || !atual){
                $("#situacao_log").val(situacao);
            }else{
                $("#situacao_log").val('');
            }
        });


        $('#form-servicos').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            $.ajax({
                url: '<?= $urlServicos; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        $.alert(retorno.msg);

                        $.ajax({
                            url: '<?= $urlServicos; ?>/visualizar.php',
                            data: {codigo: retorno.codigo},
                            success: function (response) {
                                $("#paginaHome").html(response);
                            }
                        })
                    } else {
                        $.alert(retorno.msg);
                    }
                }
            })
        });


        $("#ver_logs_situacao").click(function(){
            codigo = $(this).attr("codigo");
            $.ajax({
                url:"<?= $urlServicos; ?>/form.php",
                type:"POST",
                data:{
                    codigo,
                    acao:'situacao_log'
                },
                success:function(dados){
                    $.dialog({
                        title:"Histórico de Situações",
                        type:"primary",
                        columnClass:'col-md-8',
                        content:dados
                    });
                }
            });


        });

    });
</script>



