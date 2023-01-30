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
  margin-left:20px;
}

</style>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h4 style="color:#239ea0">Política - Painel de Controle</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">


    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/dashboard/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <h6><i class="fa-solid fa-home"></i> Dashboard</h6>
        </a>
      </div>
    </div>


    <div class="row mb-1 menu-cinza">
      <div class="col">
        <h6><i class="fa-solid fa-person-circle-plus"></i> Serviços</h6>

        <?php
        if(in_array('Certidão de Nascimento - Visualizar', $ConfPermissoes)){
        ?>
        <p>
          <a url="paginas/servicos/cn/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-angles-right"></i> Certidão de Nascimento
          </a>
        </p>
        <?php
        }
        if(in_array('Registro Geral - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/rg/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Registro Geral
        </a>
        </p>
        <?php
        }
        if(in_array('CRAS - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/cras/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> CRAS
        </a>
        </p>
        <?php
        }
        if(in_array('CR - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/cr/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> CR
        </a>
        </p>
        <?php
        }
        if(in_array('Psicologia - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/psicologia/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Psicologia
        </a>
        </p>
        <?php
        }
        if(in_array('Odontologia - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/odontologia/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Odontologia
        </a>
        </p>
        <?php
        }
        if(in_array('Jurídico - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/servicos/juridico/index.php?categoria=l" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Jurídico
        </a>
        </p>
        <?php
        }
        ?>


        <h6 class="collapse-header"><i class="fa-solid fa-suitcase-medical"></i> Saúde</h6>
        <?php
        if(in_array('Saúde - Visualizar', $ConfPermissoes)){
        $q = "select * from categorias where deletado = '0' order by descricao";
        $r = mysqli_query($con, $q);
        while($c = mysqli_fetch_object($r)){
        ?>
        <p>
        <a url="paginas/servicos/saude/index.php?categoria=<?=$c->codigo?>" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> <?=$c->descricao?>
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
        <i class="fa-solid fa-angles-right"></i> Ação Social
        </a>
        </p>
        <?php
        }
        if(in_array('Ofícios - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/oficios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Ofícios
        </a>
        </p>
        <?php
        }
        ?>


        <h6 class="collapse-header"><i class="fa-regular fa-id-card"></i> Cadastros</h6>
        <?php
        if(in_array('Assessores - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/assessores/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Assessores
        </a>
        </p>
        <?php
        }
        if(in_array('Beneficiados - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/beneficiados/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Beneficiados
        </a>
        </p>
        <?php
        }
        ?>


        <?php
        if(in_array('Relatórios', $ConfPermissoes)){
        ?>
        <h6 class="collapse-header"><i class="fa-solid fa-chart-pie"></i> Relatórios</h6>
        <p>
        <a url="paginas/relatorios/index.php?tipo=bairros" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Bairros
        </a>
        </p>
        <p>
        <a url="paginas/relatorios/index.php?tipo=servicos" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Serviços
        </a>
        </p>
        <p>
        <a url="paginas/relatorios/index.php?tipo=idade" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Idade
        </a>
        </p>
        <p>
        <a url="paginas/relatorios/index.php?tipo=sexo" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Sexo
        </a>
        </p>
        <p>
        <a url="paginas/relatorios/index.php?tipo=municipios" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Municípios
        </a>
        </p>
        <p>
        <a url="paginas/relatorios/index.php?tipo=assessores" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Assessores
        </a>
        </p>
        <?php
        }
        ?>

        <h6 class="collapse-header"><i class="fa-solid fa-gears"></i> Configurações</h6>

        <?php
        if(in_array('Fontes Locais - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/fontes_locais/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Fontes Locais
        </a>
        </p>
        <?php
        }
        if(in_array('Municípios - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/municipios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Municípios
        </a>
        </p>
        <?php
        }
        if(in_array('Bairros - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/bairros/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Bairros
        </a>
        </p>
        <?php
        }

        if(in_array('Secretarias - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/secretarias/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Secretarias
        </a>
        </p>
        <?php
        }
        if(in_array('Tipo de Serviço - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/tipo_servico/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Tipo de Serviço
        </a>
        </p>
        <?php
        }

        if(in_array('Tipo Ação Social - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/acao_social_tipo/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Tipo Ação Social
        </a>
        </p>
        <?php
        }

        if(in_array('Categorias de Serviço - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/categorias/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Categorias de Serviço
        </a>
        </p>
        <?php
        }
        if(in_array('Especialidades - Visualizar', $ConfPermissoes)){
        ?>
        <p>
        <a url="paginas/cadastros/especialidades/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Especialidades
        </a>
        </p>
        <?php
        }
        if(in_array('Usuários - Visualizar', $ConfPermissoes) or $_SESSION['usuario']['codigo'] == 1){
        ?>
        <p>
        <a url="paginas/cadastros/usuarios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Usuários
        </a>
        </p>
        <?php
        }
        if(in_array('Permissoes - Visualizar', $ConfPermissoes) or $_SESSION['usuario']['codigo'] == 1){
        ?>
        <p>
        <a url="paginas/cadastros/permissoes/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-solid fa-angles-right"></i> Permissoes
        </a>
        </p>
        <?php
        }
        ?>

      </div>
    </div>





    <!-- <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/usuarios/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
         <i class="fa-solid fa-users"></i> Usuários do Sistema
        </a>
      </div>
    </div> -->



  </div>
</div>

<script>
  $(function(){ Carregando('none');
    // $("a[url]").click(function(){
    $(document).on('click', '[url]', function (e) {
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