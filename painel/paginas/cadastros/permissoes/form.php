<?php
include "config_permissoes.php";

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
        $query = "UPDATE permissoes SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO permissoes SET {$attr}";
    }

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('permissoes', $codigo, $query);

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
    $query = "SELECT * FROM permissoes WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>

<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> Permissões
        </h6>
    </div>
    <div class="card-body">
        <form id="form-tipo-servico">

            <div class="form-group">
                <label for="tipo">Descrição <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control mb-2"
                        id="descricao"
                        name="descricao"
                        value="<?= $d->descricao; ?>"
                        required
                >

            </div>

            <div class="form-group">
                <label for="vinculo">Vínculo</label>
                <select
                        class="form-control mb-2"
                        id="vinculo"
                        name="vinculo"
                >
                    <option></option>
                    <?php
                    $query = "SELECT * FROM permissoes where vinculo = '0' ORDER BY descricao";
                    $resultP = mysqli_query($con, $query);

                    while ($p = mysqli_fetch_object($resultP)): ?>
                        <option
                            <?= ($codigo and $d->vinculo == $p->codigo) ? 'selected' : ''; ?>
                                value="<?= $p->codigo ?>"
                        ><?= $p->descricao; ?></option>
                    <?php endwhile; ?>
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
        $('#form-tipo-servico').validate();

        $('#form-tipo-servico').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            $.ajax({
                url: '<?= $urlPermissoes; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        $.alert(retorno.msg);

                        $.ajax({
                            url: '<?= $urlPermissoes; ?>/visualizar.php',
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
            url:"<?= $urlPermissoes; ?>/index.php",
            type:"POST",
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        });
    });
</script>



