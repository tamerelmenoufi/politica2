<?php
include "config_secretarias.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;

    unset($data['codigo']);

    foreach ($data as $name => $value) {
        $attr[] = "{$name} = '" . mysqli_real_escape_string($value) . "'";
    }

    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE secretarias SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO secretarias SET {$attr}";
    }

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysql_insert_id();

        sis_logs($codigo, $query, 'secretarias');

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
            'mysql_error' => mysql_error(),
        ]);
    }

    exit;
}

$codigo = $_GET['codigo'];

if ($codigo) {
    $query = "SELECT * FROM secretarias WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>

<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Cadastrar Secretarias
        </h6>
    </div>
    <div class="card-body">
        <form id="form-secretaria">
            <div class="form-group">
                <label for="esfera">Esfera <i class="text-danger">*</i></label>
                <select
                        class="form-control"
                        id="esfera"
                        name="esfera"
                        required
                >
                    <option value=""></option>
                    <?php foreach (getEsfera() as $esfera) : ?>
                        <option
                            <?= $esfera == $d->esfera ? "selected" : ""; ?>
                                value="<?= $esfera; ?>"
                        >
                            <?= $esfera; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="form-group">
                <label for="descricao">Descrição <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="descricao"
                        name="descricao"
                        value="<?= $d->descricao; ?>"
                        required
                >

            </div>

            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
</div>

<script>
    $(function(){ Carregando('none');
        $('#form-secretaria').validate();

        $('#form-secretaria').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            $.ajax({
                url: '<?= $urlSecretarias; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        tata.success('Sucesso', retorno.msg);

                        $.ajax({
                            url: '<?= $urlSecretarias; ?>/visualizar.php',
                            data: {codigo: retorno.codigo},
                            success: function (response) {
                                $('#palco').html(response);
                            }
                        })
                    } else {
                        tata.error('Error', retorno.msg);
                    }
                }
            })
        });
    });
</script>



