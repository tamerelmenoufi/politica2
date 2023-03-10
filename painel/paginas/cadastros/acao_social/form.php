<?php
include "config_acao_social.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;

    unset($data['codigo']);

    foreach ($data as $name => $value) {
        $attr[] = "{$name} = '" . Addslashes($value) . "'";
    }

    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE acao_social SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO acao_social SET {$attr}";
    }

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('acao_social', $codigo, $query);

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
    $query = "SELECT * FROM acao_social WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>
<style>
    div[NovoAssessorBG]{
        position:fixed;
        left:0;
        bottom:0;
        width:100%;
        height:100%;
        z-index:999;
        background-color:#333;
        opacity:0.5;
        display:none;
        z-index:998;
    }
    div[NovoAssessor]{
        position:relative;
        z-index:999;
        background-color:#fff;
        padding:20px;
        padding:20px;
        border-radius:10px;
        display:none;
    }
</style>

<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> Ação Social
        </h6>
    </div>
    <div class="card-body">
        <form id="form-acao-social">

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
                    <option value="novo">Novo Cadastro</option>
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
            <div NovoAssessorBG></div>
            <div NovoAssessor></div>

            <div class="form-group">
                <label for="local">Local <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control mb-2"
                        id="local"
                        name="local"
                        value="<?= $d->local; ?>"
                        maxlength="255"
                        required
                >

            </div>

            <div class="form-group">
                <label for="servicos">Serviços <i class="text-danger">*</i></label>

                <?php
                $queryServicos = "SELECT * FROM acao_social_tipo ORDER BY tipo";
                $resultServico = mysqli_query($con, $queryServicos);

                $servicos_check = explode(',', $d->servicos);

                while ($dados_servico = mysqli_fetch_object($resultServico)):
                    $isChecked = (@in_array($dados_servico->codigo, $servicos_check));
                    ?>
                    <div class="form-check">
                        <input
                                class="form-check-input servicos"
                                type="checkbox"
                                id="servicos-<?= $dados_servico->codigo; ?>"
                                value="<?= $dados_servico->codigo; ?>"
                            <?= $isChecked ? 'checked' : ''; ?>

                        >
                        <label class="form-check-label" for="servicos-<?= $dados_servico->codigo; ?>">
                            <?= $dados_servico->tipo; ?>
                        </label>
                    </div>
                <?php endwhile; ?>

            </div>

            <div class="form-group">
                <label for="descricao">Descrição<i class="text-danger">*</i></label>
                <textarea id="descricao" name="descricao" class="form-control mb-2"><?= $d->descricao ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="data">Data <i class="text-danger">*</i></label>
                        <input
                                type="datetime-local"
                                class="form-control mb-2"
                                id="data"
                                name="data"
                                value="<?= $codigo ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($d->data)) : ''; ?>"
                                required
                        >

                    </div>
                </div>
            </div>
            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>
            <button type="button" class="btn btn-danger voltar">Voltar</button>
        </form>
    </div>
</div>

<script>
    $(function(){ Carregando('none');
        $("#assessor").selectpicker();

        $('#form-acao-social').validate();

        $("#assessor").change(function(){
            valor = $(this).val();
            if(valor === 'novo'){
                $.ajax({
                    url:"paginas/cadastros/assessores/novo.php",
                    success:function(dados){
                        $("div[NovoAssessor]").html(dados);
                        $("div[NovoAssessorBG]").css("display","block");
                        $("div[NovoAssessor]").css("display","block");
                    },
                    error:function(){
                        alert('Ocorreu um erro!');
                    }
                });
            }
        });

        $("div[NovoAssessorBG]").click(function(){
            $("div[NovoAssessorBG]").css("display","none");
            $("div[NovoAssessor]").css("display","none");
            $("div[NovoAssessor]").html('');
            $("#assessor").val('');
            $("#assessor").selectpicker('refresh');
        });



        $('#form-acao-social').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            var servicos = [];

            $(".servicos").each(function (index, item) {
                console.log($(item).val());
                if ($(item).is(':checked')) {
                    servicos.push($(item).val());
                }
            });

            dados.push({name: 'servicos', value: servicos.join(',')})

            $.ajax({
                url: '<?= $acaoSocial; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        $.alert(retorno.msg);

                        $.ajax({
                            url: '<?= $acaoSocial; ?>/visualizar.php',
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
    });

    $(".voltar").click(function(){
        $.ajax({
            url:"<?= $acaoSocial; ?>/index.php",
            type:"POST",
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        });
    });
</script>



