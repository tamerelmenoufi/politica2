<?php
include_once "config_servicos.php";

if($_POST['acao'] == 'CampoBusca'){

    $_SESSION['CampoBusca'] = $_POST['busca'];

    $_SESSION['query_busca'] = " AND (
        b.nome like '%{$_SESSION['CampoBusca']}%' or
        a.nome like '%{$_SESSION['CampoBusca']}%' or
        s.situacao like '%{$_SESSION['CampoBusca']}%' or
        (CASE WHEN s.data_agenda <= NOW() AND s.situacao = 'concluido' AND s.data_agenda > 0 THEN 'Atendido' "
    . "WHEN s.data_agenda < NOW() AND s.situacao != 'concluido' AND s.data_agenda > 0 THEN 'Não atendido' "
    . "WHEN s.data_agenda > NOW() AND s.data_agenda > 0 THEN 'agendado' "
    . "ELSE 'Aguardando' "
    . "END) like '%{$_SESSION['CampoBusca']}%' or
        lf.descricao like '%{$_SESSION['CampoBusca']}%' or
        local_responsavel like '%{$_SESSION['CampoBusca']}%'
        )
    ";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['acao'] === 'excluir') {
    $codigo = $_POST['codigo'];

    if (exclusao('servicos', $codigo)) {
        echo json_encode(["status" => true, "msg" => "Registro excluído com sucesso"]);
    } else {
        echo json_encode(["status" => false, "msg" => "Error ao tentar excluír"]);
    }
    exit;
}

$query = "SELECT s.*, a.nome AS assessor, b.nome AS beneficiado FROM servicos s "
    . "LEFT JOIN assessores a ON a.codigo = s.assessor "
    . "LEFT JOIN beneficiados b ON b.codigo = s.beneficiado "
    . "WHERE s.tipo = '6' AND s.deletado = '0' "
    . "ORDER BY s.codigo DESC";



$colunaAtendimento = "(CASE WHEN s.data_agenda <= NOW() AND s.situacao = 'concluido' AND s.data_agenda > 0 THEN 'Atendido' "
    . "WHEN s.data_agenda < NOW() AND s.situacao != 'concluido' AND s.data_agenda > 0 THEN 'Não atendido' "
    . "WHEN s.data_agenda > NOW() AND s.data_agenda > 0 THEN 'agendado' "
    . "ELSE 'Aguardando' "
    . "END) AS atendimento, lf.descricao AS lf_descricao ";

$query = "SELECT s.*, a.nome AS assessor, b.nome AS beneficiado, t.descricao as especialidade, {$colunaAtendimento} FROM servicos s "
    . "LEFT JOIN assessores a ON a.codigo = s.assessor "
    . "LEFT JOIN beneficiados b ON b.codigo = s.beneficiado "
    . "LEFT JOIN especialidades t ON t.codigo = s.especialidade "
    . "LEFT JOIN local_fontes lf ON lf.codigo = s.local_fonte "
    . "WHERE s.tipo = '6' AND s.deletado = '0' ".(($_SESSION['query_busca'])?:false)
    . "ORDER BY s.codigo DESC";

$result = mysqli_query($con, $query);

$_SESSION['query_xls'] = $query;
$_SESSION['saude_xls'] = false;
?>

<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Serviços - Odontologia
        </h6>
        <?php
        if (in_array('Odontologia - Cadastrar', $ConfPermissoes)) {
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
                    <th colspan="5">
                        <div class="row d-md-flex flex-row align-items-center">
                        <?php CampoBusca($urlServicos.'/index.php'); ?>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>Beneficiado</th>
                    <th>Assessor</th>
                    <th>Data da Agenda</th>
                    <th>Situação</th>
                    <th>Local</th>
                    <th class="mw-20">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($d = mysqli_fetch_object($result)): ?>
                    <tr id="linha-<?= $d->codigo; ?>">
                        <td><?= $d->codigo; ?> - <?= $d->beneficiado; ?></td>
                        <td><?= $d->assessor; ?></td>
                        <td><?= formata_datahora($d->data_agenda, DATA_HM); ?></td>
                        <td><?= getSituacaoOptions($d->situacao); ?></td>
                        <td><?= $d->lf_descricao . (($d->local_responsavel)?' ('.$d->local_responsavel.')':false) . (($d->especialidade)?' ('.$d->especialidade.')':false); ?></td>
                        <td>
                            <button
                                    class="btn btn-sm btn-link"
                                    url="<?= $urlServicos ?>/visualizar.php?codigo=<?= $d->codigo ?>"
                            >
                                <i class="fa-regular fa-eye text-info"></i>
                            </button>
                            <?php
                            if (in_array('Odontologia - Editar', $ConfPermissoes)) {
                                ?>
                                <button
                                        class="btn btn-sm btn-link"
                                        url="<?= $urlServicos ?>/form.php?codigo=<?= $d->codigo; ?>"
                                >
                                    <i class="fa-solid fa-pencil text-warning"></i>
                                </button>
                                <?php
                            }
                            if (in_array('Odontologia - Excluir', $ConfPermissoes)) {
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
    $(function(){ Carregando('none');
        var table = $('#datatable').DataTable();

        $('#filtro-situacao').selectpicker();

        $('#filtro-situacao').change(function () {
            var val = $(this).val();
            console.log(val);
            table.column(3)
                .search(val ? '^' + $(this).val() + '$' : val, true, false)
                .draw();
        });

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
                                        $.alert(retorno.msg);
                                    } else {
                                        $.alert(retorno.msg);
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