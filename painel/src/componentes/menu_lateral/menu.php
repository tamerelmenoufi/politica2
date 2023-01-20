<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/politica/painel/lib/includes.php");

?>

<style>
.menu-cinza{
  padding:8px;
  font-size:15px;
  border-bottom:1px solid #d7d7d7;
  cursor:pointer;
}

.texto-cinza{
  color:#5e5e5e;
}

</style>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <img src="img/logomenup.png" style="height:60px;" alt="">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <h4 style="color:#239ea0">Política - Painel de Controle</h4>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/dashboard/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
      </div>
    </div>


    <div class="row mb-1 menu-cinza">
      <div class="col">
        <h6>Serviços</h6>

        <?php
        if(in_array('Certidão de Nascimento - Visualizar', $ConfPermissoes)){
        ?>
        <p>
          <a url="paginas/servicos/cn/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-chart-line"></i> Certidão de Nascimento
          </a>
        </p>
        <?php
        }
        if(in_array('Registro Geral - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/rg/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Registro Geral
        </a>
        </p>
        <?php
        }
        if(in_array('CRAS - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/cras/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> CRAS
        </a>
        </p>
        <?php
        }
        if(in_array('CR - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/cr/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> CR
        </a>
        </p>
        <?php
        }
        if(in_array('Psicologia - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/psicologia/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Psicologia
        </a>
        </p>
        <?php
        }
        if(in_array('Odontologia - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/odontologia/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Odontologia
        </a>
        </p>
        <?php
        }
        if(in_array('Jurídico - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/juridico/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Jurídico
        </a>
        </p>
        <?php
        }
        ?>
      </div>
    </div>


    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/usuarios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
         <i class="fa-solid fa-users"></i> Usuários do Sistema
        </a>
      </div>
    </div>



  </div>
</div>

<script>
  $(function(){
    $("a[url]").click(function(){
      Carregando();
      url = $(this).attr("url");
      $.ajax({
        url,
        success:function(dados){
          $("#paginaHome").html(dados);
        }
      });
    });
  })
</script>