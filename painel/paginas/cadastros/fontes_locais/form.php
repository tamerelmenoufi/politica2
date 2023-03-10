<?php
include "config_fontes_locais.php";

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
        $query = "UPDATE local_fontes SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO local_fontes SET {$attr}";
    }

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('local_fontes', $codigo, $query);

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
    $query = "SELECT * FROM local_fontes WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>

<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> Fontes locais
        </h6>
    </div>
    <div class="card-body">
        <form id="form-municipio">

            <div class="form-group">
                <label for="servico_tipo">
                    Tipo de Serviço <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control mb-2"
                        id="servico_tipo"
                        name="servico_tipo"
                        data-live-search="true"
                        data-none-selected-text="Selecione"
                        required
                >
                    <option value=""></option>
                    <?php
                    $query = "SELECT * FROM servico_tipo where deletado = '0' ORDER BY tipo";
                    $result = mysqli_query($con, $query);

                    while ($s = mysqli_fetch_object($result)): ?>
                        <option
                            <?= ($codigo and $d->servico_tipo == $s->codigo) ? 'selected' : ''; ?>
                                value="<?= $s->codigo ?>">
                            <?= $s->tipo; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div OpcCategoria class="form-group">
                <label for="servico_tipo">
                    Categoria <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control mb-2"
                        id="categoria"
                        name="categoria"
                        data-live-search="true"
                        data-none-selected-text="Selecione"
                        required
                >
                    <option value=""></option>
                    <?php
                    $query = "SELECT * FROM categorias where deletado = '0' ORDER BY descricao";
                    $result = mysqli_query($con, $query);

                    while ($s = mysqli_fetch_object($result)): ?>
                        <option
                            <?= ($codigo and $d->categoria == $s->codigo) ? 'selected' : ''; ?>
                                value="<?= $s->codigo ?>">
                            <?= $s->descricao; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição<i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control mb-2"
                        id="descricao"
                        name="descricao"
                        maxlength="255"
                        value="<?= $d->descricao; ?>"
                        required
                >
            </div>

            <div class="form-group">
                <label for="senha">Chave de Acesso<i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control mb-2"
                        id="senha"
                        name="senha"
                        maxlength="255"
                        value="<?= $d->senha; ?>"
                        required
                >
            </div>

            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>
            <button type="button" class="btn btn-danger voltar">Voltar</button>
        </form>
    </div>
</div>

<script>
    $(function(){ Carregando('none');

        $('#servico_tipo').selectpicker();

        if($("#servico_tipo").val() == 7){
            $("div[OpcCategoria]").css("display","block");
        }else{
            $("div[OpcCategoria]").css("display","none");
        }

        $("#servico_tipo").change(function(){
            if($(this).val() == 7){
                $("#categoria").val('');
                $("div[OpcCategoria]").css("display","block");
            }else{
                $("#categoria").val('');
                $("div[OpcCategoria]").css("display","none");
            }
        });

        $('#form-municipio').validate();

        $('#form-municipio').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            $.ajax({
                url: '<?= $fontesLocais; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        $.alert(retorno.msg);

                        $.ajax({
                            url: '<?= $fontesLocais; ?>/visualizar.php',
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
            url:"<?= $fontesLocais; ?>/index.php",
            type:"POST",
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        });
    });
</script>