<?php
include "config_fontes_locais.php";
$codigo = $_GET['codigo'];
$query = "SELECT lf.*, st.tipo AS tipo FROM especialidades lf "
    . "LEFT JOIN servico_tipo st ON st.codigo = lf.servico_tipo "
    . "WHERE lf.codigo = '{$codigo}'";
$result = mysqli_query($con, $query);
$d = mysqli_fetch_object($result);

?>

<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Visualizar
        </h6>
        <div class="d-block">
            <?php
            if(in_array('Especialidades - Cadastrar', $ConfPermissoes)){
            ?>
            <button
                    type="button"
                    class="btn btn-success btn-sm float-left"
                    url="<?= $especialidades ?>/form.php"
                    style="margin-right: 2px"
            >
                <i class="fa-solid fa-plus"></i> Novo
            </button>
            <?php
            }
            if(in_array('Especialidades - Editar', $ConfPermissoes)){
            ?>
            <button
                    type="button"
                    class="btn btn-warning btn-sm float-left"
                    url="<?= $especialidades ?>/form.php?codigo=<?= $codigo; ?>"
                    style="margin-right: 2px"
            >
                <i class="fa-solid fa-pencil"></i> Editar
            </button>
            <?php
            }
            if(in_array('Especialidades - Excluir', $ConfPermissoes)){
            ?>
            <button
                    type="button"
                    class="btn btn-danger btn-excluir btn-sm float-left"
                    data-codigo="<?= $codigo; ?>"
            >
                <i class="fa-regular fa-trash-can"></i> Excluir
            </button>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 font-weight-bold">Tipo de Serviço</div>
            <div class="col-md-8"><?= $d->tipo; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Descricao</div>
            <div class="col-md-8"><?= $d->descricao; ?></div>
        </div>

    </div>
</div>

<script>
    Carregando('none');
    $('.btn-excluir').click(function () {
        var codigo = $(this).data('codigo');

        $.confirm({
            title: 'Aviso',
            content: 'Deseja excluir este registro?',
            type: 'red',
            icon: 'fa fa-warning',
            buttons: {
                sim: {
                    text: 'Sim',
                    btnClass: 'btn-red',
                    action: function () {
                        $.ajax({
                            url: '<?= $especialidades;?>/index.php',
                            method: 'POST',
                            data: {
                                acao: 'excluir',
                                codigo
                            },
                            success: function (response) {
                                let retorno = JSON.parse(response);

                                if (retorno.status) {
                                    $.alert(retorno.msg);

                                    $.ajax({
                                        url: '<?= $especialidades; ?>/index.php',
                                        success: function (response) {
                                            $("#paginaHome").html(response);
                                        }
                                    });
                                } else {
                                    $.alert(retorno.msg);
                                }
                            }
                        })
                    }
                },
                nao: {
                    text: 'Não'
                }
            }
        })
    });
</script>
