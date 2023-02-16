<?php
include "config_assessores.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;

    unset($data['codigo']);

    if (!$codigo) $data['data_cadastro'] = 'NOW()';

    foreach ($data as $name => $value) {
        $attr[] = "{$name} = '" . addslashes($value) . "'";
    }

    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE assessores SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO assessores SET {$attr}";
    }

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('assessores', $codigo, $query);

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
    $query = "SELECT * FROM assessores WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>

<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> assessores
        </h6>
    </div>
    <div class="card-body">
        <form id="form-assessores">
            <div class="form-group">
                <label for="nome">Nome <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control mb-2"
                        id="nome"
                        name="nome"
                        value="<?= $d->nome; ?>"
                        required
                >
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cpf">CPF <i class="text-danger"></i></label>
                        <input
                                type="text"
                                class="form-control mb-2"
                                id="cpf"
                                name="cpf"
                                value="<?= $d->cpf; ?>"
                        >

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="data_nascimento">
                            Data de Nascimento <i class="text-danger">*</i>
                        </label>
                        <input
                                type="date"
                                class="form-control mb-2"
                                id="data_nascimento"
                                name="data_nascimento"
                                value="<?= $d->data_nascimento; ?>"
                                required
                        >

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sexo">Sexo <i class="text-danger">*</i></label>
                        <select
                                class="form-control mb-2"
                                id="sexo"
                                name="sexo"
                                required
                        >
                            <option value=""></option>
                            <?php foreach (getSexo() as $key => $sexo) : ?>
                                <option
                                    <?= ($codigo and $d->sexo == $key) ? "selected" : ""; ?>
                                        value="<?= $key; ?>"
                                >
                                    <?= $sexo; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="email">
                            E-Mail <i class="text-danger"></i>
                        </label>
                        <input
                                type="email"
                                class="form-control mb-2"
                                id="email"
                                name="email"
                                value="<?= $d->email; ?>"
                        >

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="telefone">
                            Telefone <i class="text-danger">*</i>
                        </label>
                        <input
                                type="text"
                                class="form-control mb-2"
                                id="telefone"
                                name="telefone"
                                value="<?= $d->telefone; ?>"
                                required
                        >

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="municipio">
                            Municipio <i class="text-danger">*</i>
                        </label>
                        <select
                                class="form-control mb-2"
                                id="municipio"
                                name="municipio"
                                data-live-search="true"
                                required
                        >
                            <option value=""></option>
                            <?php
                            $query = "SELECT * FROM municipios";
                            $result = mysqli_query($con, $query);

                            while ($m = mysqli_fetch_object($result)): ?>
                                <option
                                    <?= ($codigo and $d->municipio == $m->codigo) ? 'selected' : ''; ?>
                                        value="<?= $m->codigo ?>">
                                    <?= $m->municipio; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bairro">
                            Bairro <i class="text-danger">*</i>
                        </label>
                        <div id="container-bairro">
                            <select
                                    class="form-control bairro"
                                    id="bairro"
                                    name="bairro"
                                    data-live-search="true"
                                    data-none-selected-text="Selecione"
                                    required
                            >
                                <option value="">:: Selecione ::</option>
                                <?php
                                // if ($codigo):
                                    $query = "SELECT * FROM bairros where deletado != '1' ORDER BY descricao";
                                    $result = mysqli_query($con, $query);

                                    while ($s = mysqli_fetch_object($result)): ?>
                                        <option
                                            <?= ($codigo and $d->bairro == $s->codigo) ? 'selected' : ''; ?>
                                                value="<?= $s->codigo ?>">
                                            <?= $s->descricao; ?>
                                        </option>
                                    <?php
                                    endwhile;
                                // endif;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="endereco">
                            Endereco <i class="text-danger">*</i>
                        </label>
                        <input
                                type="text"
                                class="form-control mb-2"
                                id="endereco"
                                name="endereco"
                                value=" <?= $d->endereco; ?>"
                                required
                        >

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="responsavel">
                    Responsavel <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control mb-2"
                        id="responsavel"
                        name="responsavel"
                        required
                >
                    <option value=""></option>

                    <?php foreach (getResponsavel() as $key => $responsavel) : ?>
                        <option
                            <?= ($codigo and $d->responsavel == $key) ? "selected" : ""; ?>
                                value="<?= $key; ?>"
                        >
                            <?= $responsavel; ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="esfera">
                            Esfera <i class="text-danger">*</i>
                        </label>
                        <select
                                class="form-control mb-2"
                                id="esfera"
                                name="esfera"
                                required
                        >
                            <option value="">:: Selecione ::</option>
                            <?php
                            foreach (getEsfera() as $value): ?>
                                <option
                                    <?= ($codigo and $d->esfera == $value) ? 'selected' : ''; ?>
                                        value="<?= $value; ?>">
                                    <?= $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="secretaria">
                            Secretaria <i class="text-danger">*</i>
                        </label>
                        <div id="container-secretaria">
                            <select
                                    class="form-control secretaria"
                                    id="secretaria"
                                    name="secretaria"
                                    data-live-search="true"
                                    data-none-selected-text="Selecione"
                                    required
                            >
                                <option value="">:: Selecione ::</option>
                                <?php
                                if ($codigo):
                                    $query = "SELECT * FROM secretarias WHERE esfera = '{$d->esfera}' ORDER BY descricao";
                                    $result = mysqli_query($con, $query);

                                    while ($s = mysqli_fetch_object($result)): ?>
                                        <option
                                            <?= ($codigo and $d->secretaria == $s->codigo) ? 'selected' : ''; ?>
                                                value="<?= $s->codigo ?>">
                                            <?= $s->descricao; ?>
                                        </option>
                                    <?php
                                    endwhile;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="indicacao">
                            Indicação <i class="text-danger">*</i>
                        </label>
                        <input
                                type="text"
                                class="form-control mb-2"
                                id="indicacao"
                                name="indicacao"
                                value=" <?= $d->indicacao; ?>"
                                required
                        >
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="situacao">Situação <i class="text-danger">*</i></label>
                <select
                        class="form-control mb-2"
                        id="situacao"
                        name="situacao"
                        required
                >
                    <option value="">:: Selecione ::</option>
                        <option
                            <?= '0' == $d->situacao ? "selected" : ""; ?>
                                value="0"
                        >
                            Desativado
                        </option>
                        <option
                            <?= '1' == $d->situacao ? "selected" : ""; ?>
                                value="1"
                        >
                            Ativado
                        </option>
                </select>

            </div>

            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>
            <button type="button" class="btn btn-danger voltar">Voltar</button>
        </form>
    </div>
</div>

<script>
    $(function(){ Carregando('none');
        $("#cpf").mask("999.999.999-99");

        $('#telefone').mask('(99) 9 9999-9999');

        // $('#municipio').selectpicker();

        // $('#form-assessores').validate();

        $('#form-assessores').submit(function (e) {
            e.preventDefault();

            // if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            $.ajax({
                url: '<?= $urlAssessores; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        $.alert(retorno.msg);

                        $.ajax({
                            url: '<?= $urlAssessores; ?>/visualizar.php',
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


        $('#esfera').change(function () {
            var valor = $(this).val();
            $.ajax({
                url: '<?= $urlAssessores; ?>/select_secretarias.php',
                data: {esfera: valor},
                success: function (response) {
                    $('#container-secretaria').html(response);
                },
                error:function(){
                    alert('Erro no carregamento')
                }
            })
        });

    });

    $(".voltar").click(function(){
        $.ajax({
            url:"<?= $urlAssessores; ?>/index.php",
            type:"POST",
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        });
    });
</script>



