<?php

include "config_beneficiados.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['acao'] === 'excluir') {
    $codigo = $_POST['codigo'];

    if (exclusao('beneficiados', $codigo)) {
        echo json_encode(["status" => true, "msg" => "Registro excluído com sucesso"]);
    } else {
        echo json_encode(["status" => false, "msg" => "Error ao tentar excluír"]);
    }
    exit;
}

echo $query = "SELECT b.*, m.municipio AS municipio FROM beneficiados b "
    . "LEFT JOIN municipios m ON m.codigo = b.municipio "
    . "WHERE b.deletado = '0' "
    . "ORDER BY codigo desc limit 100";
$result = mysqli_query($con, $query);

?>


<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Beneficiados
        </h6>
        <?php
        if (in_array('Beneficiados - Cadastrar', $ConfPermissoes)) {
            ?>
            <button type="button" class="btn btn-success btn-sm" url="paginas/cadastros/beneficiados/form.php">
                <i class="fa-solid fa-plus"></i> Novo
            </button>
            <?php
        }
        ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table id="datatable" class="table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Município</th>
                    <th class="mw-20">Ações</th>
                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>
    </div>
</div>

<script>
    $(function(){ Carregando('none');
        dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "retrieve": true,
            "paging": true,
            "stateSave": true,
            "ajax": {
                url: "<?= $urlBeneficiados; ?>/fetch.php",
                method: "POST",
            },
            "columnDefs": [
                {
                    "targets": 3,
                    "orderable": false,
                },
            ],
        });

        $("#datatable").on("click", "tbody tr td .btn-excluir", function () {
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
                                url: '<?= $urlBeneficiados;?>/index.php',
                                method: 'POST',
                                data: {
                                    acao: 'excluir',
                                    codigo
                                },
                                success: function (response) {
                                    let retorno = JSON.parse(response);

                                    if (retorno.status) {
                                        tata.success('Sucesso', retorno.msg);
                                        //$(`#linha-${codigo}`).remove();
                                        $(this).parent().parent().remove();
                                    } else {
                                        tata.error('Error', retorno.msg);
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
    });
</script>