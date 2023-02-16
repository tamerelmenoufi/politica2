<?php
include "config_assessores.php";

$codigo = $_GET['codigo'];
$query = "SELECT a.*, m.municipio AS municipio, s.descricao as secretaria_nome, b.descricao as bairro_nome FROM assessores a "
    . "LEFT JOIN municipios m ON m.codigo = a.municipio "
    . "LEFT JOIN secretarias s ON s.codigo = a.secretaria "
    . "LEFT JOIN bairros b ON b.codigo = a.bairro "
    . "WHERE a.codigo = '{$codigo}'";
$result = mysqli_query($con, $query);
$d = mysqli_fetch_object($result);

?>


<div class="card shadow m-3">
    <div class="card-header py-3 d-flex flex-md-row flex-column align-items-center justify-content-md-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Visualizar
        </h6>
        <div class="d-md-flex justify-content-xl-center">
            <?php
            if(in_array('Assessores - Cadastrar', $ConfPermissoes)){
            ?>
            <button
                    type="button"
                    class="btn btn-success btn-sm float-left"
                    url="<?= $urlAssessores ?>/form.php"
                    style="margin-right: 2px"
            >
                <i class="fa-solid fa-plus"></i> Novo
            </button>
            <?php
            }
            if(in_array('Assessores - Editar', $ConfPermissoes)){
            ?>
            <button
                    type="button"
                    class="btn btn-warning btn-sm float-left"
                    url="<?= $urlAssessores ?>/form.php?codigo=<?= $codigo; ?>"
                    style="margin-right: 2px"
            >
                <i class="fa-solid fa-pencil"></i> Editar
            </button>
            <?php
            }
            if(in_array('Assessores - Excluir', $ConfPermissoes)){
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
             <button type="button" class="btn btn-secondary voltar btn-sm">
            <i class="fa fa-house"></i> 
             Voltar</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 font-weight-bold">Nome</div>
            <div class="col-md-8"><?= $d->nome; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">CPF</div>
            <div class="col-md-8"><?= $d->cpf; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Data de nascimento</div>
            <div class="col-md-8"><?= formata_datahora($d->data_nascimento, DATA) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Sexo</div>
            <div class="col-md-8"><?= getSexoOptions($d->sexo); ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">E-Mail</div>
            <div class="col-md-8"><?= $d->email; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Telefone</div>
            <div class="col-md-8"><?= $d->telefone; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Município</div>
            <div class="col-md-8"><?= $d->municipio; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Bairro</div>
            <div class="col-md-8"><?= $d->bairro_nome; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Endereço</div>
            <div class="col-md-8"><?= $d->endereco; ?></div>
        </div>
        <div class="row">
            <div class="col-md-4 font-weight-bold">Responsável</div>
            <div class="col-md-8"><?= $d->responsavel; ?></div>
        </div>

        <div class="row">
            <div class="col-md-4 font-weight-bold">Esfere</div>
            <div class="col-md-8"><?= $d->esfera; ?></div>
        </div>

        <div class="row">
            <div class="col-md-4 font-weight-bold">Secretaria</div>
            <div class="col-md-8"><?= $d->secretaria_nome; ?></div>
        </div>

        <div class="row">
            <div class="col-md-4 font-weight-bold">Indicação</div>
            <div class="col-md-8"><?= $d->indicacao; ?></div>
        </div>

        <div class="row">
            <div class="col-md-4 font-weight-bold">Situação</div>
            <div class="col-md-8" style="color:<?=(($d->situacao == '1')?'green':'red')?>">
                <?=(($d->situacao == '1')?'Ativado':'Desativado')?>
            </div><!-- -->
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
                            url: '<?= $urlAssessores;?>/index.php',
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
                                        url: '<?= $urlAssessores; ?>/index.php',
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
    $(".voltar").click(function(){
        $.ajax({
            url:"<?= $urlOficios; ?>/index.php",
            type:"POST",
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        });
    });
</script>
