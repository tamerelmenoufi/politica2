<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/politica/painel/lib/includes.php");

    if($_GET['s']){
        $_SESSION = [];
        header("location:./");
        exit();
    }

    if($_SESSION['PoliticaPainel']){
        $url = "src/home/index.php";
    }else{
        $url = "src/login/index.php";
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <title>Política - Painel de controle</title>
    <?php
    include("lib/header.php");
    ?>
  </head>
  <style>
body {

    background:#16999a;

}
td{
    white-space:nowrap;
}
</style>

  <body>

    <div class="Carregando">
        <div><i class="fa-solid fa-rotate fa-pulse"></i></div>
    </div>

    <div class="CorpoApp"></div>

    <?php
    include("lib/footer.php");
    ?>

    <script>
        $(function(){ Carregando('none');
            Carregando();
            $.ajax({
                url:"<?=$url?>",
                success:function(dados){
                    $(".CorpoApp").html(dados);
                }
            });
        })


        //Jconfirm
        jconfirm.defaults = {
            typeAnimated: true,
            type: "blue",
            smoothContent: true,
        }

    </script>

  </body>
</html>