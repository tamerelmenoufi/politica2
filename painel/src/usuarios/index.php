<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/politica/painel/lib/includes.php");

    if($_POST['delete']){
      $query = "update usuarios set deletado = '1' where codigo = '{$_POST['delete']}'";
      mysqli_query($con, $query);
    }

    if($_POST['status']){
      $query = "update usuarios set status = '{$_POST['opc']}' where codigo = '{$_POST['status']}'";
      mysqli_query($con, $query);
      exit();
    }
?>


<style>
  td{
    white-space: nowrap;
  }
</style>
<div class="col">
  <div class="m-3">


    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">Lista de Usuários</h5>
          <div class="card-body">
            <div style="display:flex; justify-content:end">
                <button
                    novoCadastro
                    class="btn btn-success"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"
                >Novo</button>
            </div>

<div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th scope="col">CPF</th>
                  <th scope="col">Telefone</th>
                  <th scope="col">E-mail</th>
                  <th scope="col">Situação</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = "select * from usuarios where deletado != '1' order by nome asc";
                  $result = mysqli_query($con, $query);
                  while($d = mysqli_fetch_object($result)){
                ?>
                <tr>
                  <td><?=$d->nome?></td>
                  <td><?=$d->cpf?></td>
                  <td><?=$d->telefone?></td>
                  <td><?=$d->email?></td>
                  <td>

                  <div class="form-check form-switch">
                    <input class="form-check-input status" type="checkbox" <?=(($d->codigo == 1)?'disabled':false)?> <?=(($d->status)?'checked':false)?> usuario="<?=$d->codigo?>">
                  </div>

                  </td>
                  <td>
                    <button
                      class="btn btn-primary"
                      style="margin-bottom:1px"
                      edit="<?=$d->codigo?>"
                      data-bs-toggle="offcanvas"
                      href="#offcanvasDireita"
                      role="button"
                      aria-controls="offcanvasDireita"
                    >
                      Editar
                    </button>
                    <?php
                    if($d->codigo != 1){
                    ?>
                    <button class="btn btn-danger" delete="<?=$d->codigo?>">
                      Excluir
                    </button>
                    <?php
                    }
                    ?>
                  </td>
                </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
                </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


<script>
    $(function(){ Carregando('none');
        Carregando('none');
        $("button[novoCadastro]").click(function(){
            $.ajax({
                url:"src/usuarios/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        $("button[edit]").click(function(){
            cod = $(this).attr("edit");
            $.ajax({
                url:"src/usuarios/form.php",
                type:"POST",
                data:{
                  cod
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        $("button[delete]").click(function(){
            deletar = $(this).attr("delete");
            $.confirm({
                content:"Deseja realmente excluir o cadastro ?",
                title:false,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"src/usuarios/index.php",
                            type:"POST",
                            data:{
                                delete:deletar
                            },
                            success:function(dados){
                              // $.alert(dados);
                              $("#paginaHome").html(dados);
                            }
                        })
                    },
                    'NÃO':function(){

                    }
                }
            });

        })


        $(".status").change(function(){

            status = $(this).attr("usuario");
            opc = false;

            if($(this).prop("checked") == true){
              opc = '1';
            }else{
              opc = '0';
            }


            $.ajax({
                url:"src/usuarios/index.php",
                type:"POST",
                data:{
                    status,
                    opc
                },
                success:function(dados){
                    // $("#paginaHome").html(dados);
                }
            })

        });

    })
</script>