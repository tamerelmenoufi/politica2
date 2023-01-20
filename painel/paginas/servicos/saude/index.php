<?php
include_once "config_servicos.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['acao'] === 'excluir') {
    $codigo = $_POST['codigo'];

    if (exclusao('servicos', $codigo)) {
        echo json_encode(["status" => true, "msg" => "Registro excluído com sucesso"]);
    } else {
        echo json_encode(["status" => false, "msg" => "Error ao tentar excluír"]);
    }
    exit;
}

$colunaAtendimento = "(CASE WHEN s.data_agenda <= NOW() AND s.situacao = 'concluido' AND s.data_agenda > 0 THEN 'Atendido' "
    . "WHEN s.data_agenda < NOW() AND s.situacao != 'concluido' AND s.data_agenda > 0 THEN 'Não atendido' "
    . "WHEN s.data_agenda > NOW() AND s.data_agenda > 0 THEN 'agendado' "
    . "ELSE 'Aguardando' "
    . "END) AS atendimento, lf.descricao AS lf_descricao ";

$query = "SELECT s.*, a.nome AS assessor, b.nome AS beneficiado, {$colunaAtendimento} FROM servicos s "
    . "LEFT JOIN assessores a ON a.codigo = s.assessor "
    . "LEFT JOIN beneficiados b ON b.codigo = s.beneficiado "
    . "LEFT JOIN local_fontes lf ON lf.codigo = s.local_fonte "
    . "WHERE s.tipo = '7' AND s.deletado = '0' AND s.categoria = '{$categoria}'"
    . "ORDER BY s.codigo DESC";

$result = mysqli_query($con, $query);

$_SESSION['query_xls'] = $query;
$_SESSION['saude_xls'] = true;
?>

<style>
    .bootstrap-select .dropdown-menu {
        padding: 5px;
        width: auto !important;
    }

    .bootstrap-select .dropdown-menu > li {

    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item"><a href="#" url="content.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page">Saúde</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Serviços - Saúde (<?= $cat_desc ?>)
        </h6>
        <?php
        if (in_array('Saúde - Cadastrar', $ConfPermissoes)) {
        ?>
        <span>
        <?php

            include("../../csv/download.php");
            ?>
            <button type="button" class="btn btn-success btn-sm" url="<?= $urlServicos; ?>/form.php">
                <i class="fa-solid fa-plus"></i> Novo
            </button>
        </span>
            <?php
        }
        ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table id="datatable" class="table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="6">
                        <div class="row d-md-flex flex-row align-items-center">
                            <label>Filtros: </label>
                            <div class="col-12 col-md-3">
                                <div class="form-group mb-2">

                                    <select
                                            id="filtro-situacao"
                                            class="form-control filtro-situacao"
                                            title="Situação"
                                            data-width="100%"
                                    >
                                        <?php
                                        foreach (getSituacao() as $key => $value):
                                            echo "<option value=\"{$value}\">{$value}</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="col-md-3">
                                <div class="form-group mb-2">

                                    <select
                                            id="filtro-atendimento"
                                            class="form-control filtro-atendimento"
                                            title="Atendimento"
                                            data-width="100%"
                                    >
                                        <?php
                                        foreach (getAtendimento() as $value):
                                            echo "<option value=\"{$value}\">{$value}</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>Beneficiado</th>
                    <th>Especialidade</th>
                    <th>Assessor</th>
                    <th>Data da Agenda</th>
                    <th>Situação</th>
                    <th>Local</th>
                    <th class="mw-20">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($d = mysql_fetch_object($result)): ?>
                    <tr id="linha-<?= $d->codigo; ?>">
                        <td><?= $d->beneficiado; ?></td>
                        <td><?= $d->especialista; ?></td>
                        <td><?= $d->assessor; ?></td>
                        <td><?= formata_datahora($d->data_agenda, DATA_HM); ?></td>
                        <td><?= getSituacaoOptions($d->situacao); ?></td>
                        <td><?= $d->lf_descricao . (($d->local_responsavel)?' ('.$d->local_responsavel.')':false); ?></td>
                        <td>
                            <button
                                    class="btn btn-sm btn-link"
                                    url="<?= $urlServicos ?>/visualizar.php?codigo=<?= $d->codigo ?>"
                            >
                                <i class="fa-regular fa-eye text-info"></i>
                            </button>
                            <?php
                            if (in_array('Saúde - Editar', $ConfPermissoes)) {
                                ?>
                                <button
                                        class="btn btn-sm btn-link"
                                        url="<?= $urlServicos ?>/form.php?codigo=<?= $d->codigo; ?>"
                                >
                                    <i class="fa-solid fa-pencil text-warning"></i>
                                </button>
                                <?php
                            }
                            if (in_array('Saúde - Excluir', $ConfPermissoes)) {
                                ?>
                                <button class="btn btn-sm btn-link btn-excluir" data-codigo="<?= $d->codigo ?>">
                                    <i class="fa-regular fa-trash-can text-danger"></i>
                                </button>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<script>
    $(function () {
        var table = $("#datatable").DataTable();

        $('#filtro-situacao').selectpicker();

        $('#filtro-atendimento').selectpicker();

        $('#filtro-situacao').change(function () {
            var val = $(this).val();

            table.column(4)
                .search(val ? '^' + $(this).val() + '$' : val, true, false)
                .draw();
        });

        // $('#filtro-atendimento').change(function () {
        //     var val = $(this).val();

        //     table.column(5)
        //         .search(val ? '^' + $(this).val() + '$' : val, true, false)
        //         .draw();
        // });

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
                                url: '<?= $urlServicos;?>/index.php',
                                method: 'POST',
                                data: {
                                    acao: 'excluir',
                                    codigo
                                },
                                success: function (response) {
                                    let retorno = JSON.parse(response);

                                    if (retorno.status) {
                                        tata.success('Sucesso', retorno.msg);
                                    } else {
                                        tata.error('Error', retorno.msg);
                                    }

                                    $(`#linha-${codigo}`).remove();
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
    });
</script>