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


        <h6 class="collapse-header">Saúde</h6>
        <?php
        if(in_array('Saúde - Visualizar', $ConfPermissoes)){
        $q = "select * from categorias where deletado = '0' order by descricao";
        $r = mysqli_query($con, $q);
        while($c = mysqli_fetch_object($r)){
        ?>
        <p>
        <a url="paginas/servicos/saude/index.php?categoria=<?=$c->codigo?>" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> <?=$c->descricao?>
        </a>
        </p>
        <?php
        }
        }
        ?>

        <?php
        if(in_array('Ação Social - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/acao_social/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Ação Social
        </a>
        </p>
        <?php
        }
        if(in_array('Ofícios - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/oficios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Ofícios
        </a>
        </p>
        <?php
        }
        ?>


        <h6 class="collapse-header">Cadastros</h6>
        <?php
        if(in_array('Assessores - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/assessores/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Assessores
        </a>
        </p>
        <?php
        }
        if(in_array('Beneficiados - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/beneficiados/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Beneficiados
        </a>
        </p>
        <?php
        }
        ?>


        <?php
        if(in_array('Relatórios', $ConfPermissoes)){
        ?>
        <h6 class="collapse-header">Relatórios</h6>
        <a url="paginas/relatorios/index.php?tipo=bairros" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Bairros
        </a>

        <a url="paginas/relatorios/index.php?tipo=servicos" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Serviços
        </a>

        <a url="paginas/relatorios/index.php?tipo=idade" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Idade
        </a>

        <a url="paginas/relatorios/index.php?tipo=sexo" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Sexo
        </a>

        <a url="paginas/relatorios/index.php?tipo=municipios" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Municípios
        </a>

        <a url="paginas/relatorios/index.php?tipo=assessores" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Assessores
        </a>
        <?php
        }
        ?>

        <h6 class="collapse-header">Relatórios</h6>

        <?php
        if(in_array('Fontes Locais - Visualizar', $ConfPermissoes)){
        ?>
        <a class="collapse-item" href="#" url=""></a>
        <a url="paginas/cadastros/fontes_locais/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i>Fontes Locais
        </a>
        <?php
        }
        if(in_array('Municípios - Visualizar', $ConfPermissoes)){
        ?>
        <a url="paginas/cadastros/municipios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i>Municípios
        </a>
        <?php
        }
        if(in_array('Bairros - Visualizar', $ConfPermissoes)){
        ?>
        <a url="paginas/cadastros/bairros/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line">Bairros</i>
        </a>
        <?php
        }

        if(in_array('Secretarias - Visualizar', $ConfPermissoes)){
        ?>
        <a url="paginas/cadastros/secretarias/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i>Secretarias
        </a>
        <?php
        }
        if(in_array('Tipo de Serviço - Visualizar', $ConfPermissoes)){
        ?>
        <a url="paginas/cadastros/tipo_servico/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i>Tipo de Serviço
        </a>
        <?php
        }

        if(in_array('Tipo Ação Social - Visualizar', $ConfPermissoes)){
        ?>
        <a url="paginas/cadastros/acao_social_tipo/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i>Tipo Ação Social
        </a>
        <?php
        }

        if(in_array('Categorias de Serviço - Visualizar', $ConfPermissoes)){
        ?>
        <a url="paginas/cadastros/categorias/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i>Categorias de Serviço
        </a>
        <?php
        }
        if(in_array('Especialidades - Visualizar', $ConfPermissoes)){
        ?>
        <a url="paginas/cadastros/especialidades/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i>Especialidades
        </a>
        <?php
        }
        if(in_array('Usuários - Visualizar', $ConfPermissoes) or $_SESSION['usuario']['codigo'] == 1){
        ?>
        <a url="paginas/cadastros/usuarios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Usuários
        </a>
        <?php
        }
        if(in_array('Permissoes - Visualizar', $ConfPermissoes) or $_SESSION['usuario']['codigo'] == 1){
        ?>
        <a url="paginas/cadastros/permissoes/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> Permissoes
        </a>
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