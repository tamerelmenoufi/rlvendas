<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['filtro'] == 'filtrar'){
        $_SESSION['dashboardDataInicial'] = $_POST['dashboardDataInicial'];
        $_SESSION['dashboardDataFinal'] = $_POST['dashboardDataFinal'];
      }elseif($_POST['filtro']){
        $_SESSION['dashboardDataInicial'] = false;
        $_SESSION['dashboardDataFinal'] = false;
      }
  
      if($_SESSION['dashboardDataInicial'] and $_SESSION['dashboardDataFinal']){
        $where = " and dataCriacao between '{$_SESSION['dashboardDataInicial']} 00:00:00' and '{$_SESSION['dashboardDataFinal']} 23:59:59' ";

      }

    $query = " SELECT
            (select count(*) from produtos where situacao = '1' and deletado != '1') as quantidade_produtos,
            (select count(*) from vendas where situacao = 'pago') as quantidade_vendas,
            (select sum(total) from vendas where situacao = 'pago') as total_vendas
    ";
    $result = mysqli_query($con,$query);

    $d = mysqli_fetch_object($result);
    
?>
<style>

</style>


<div class="m-3">


    <div class="row g-0 mb-3 mt-3">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-text">Filtro por Período </label>
                <label class="input-group-text" for="data_inicial"> De </label>
                <input type="date" id="data_inicial" class="form-control" <?=$busca_disabled?> value="<?=$_SESSION['dashboardDataInicial']?>" >
                <label class="input-group-text" for="data_final"> A </label>
                <input type="date" id="data_final" class="form-control" value="<?=$_SESSION['dashboardDataFinal']?>" >
                <button filtro="filtrar" class="btn btn-outline-secondary" type="button">Buscar</button>
                <button filtro="limpar" class="btn btn-outline-danger" type="button">limpar</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-0">
        <div class="col-md-12 p-2">
            <h6>Resumo Geral</h6>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-warning" role="alert">
                <span>Produtos</span>
                <h1><?=number_format($d->quantidade_produtos,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-success" role="alert">
                <span>Vendas</span>
                <h1><?=number_format($d->quantidade_vendas,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Total de Vendas</span>
                <!-- <h1>R$ <?=number_format($d->total_vendas,2,',','.')?></h1> -->
                <h1>R$ ***,**</h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-secondary" role="alert">
                <span>Ticket Médio</span>
                <h1>R$ <?=number_format($d->total_vendas/$d->quantidade_vendas,2,',','.')?></h1>
            </div>
        </div>
        
    </div>
</div>


<script>
    $(function(){
        Carregando('none')

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          dashboardDataInicial = $("#data_inicial").val();
          dashboardDataFinal = $("#data_final").val();
          Carregando()
          $.ajax({
              url:"src/dashboard/index.php",
              type:"POST",
              data:{
                  filtro,
                  dashboardDataInicial,
                  dashboardDataFinal
              },
              success:function(dados){
                  $("#paginaHome").html(dados);
              }
          })
        })

        $("button[limpar]").click(function(){
          Carregando()
          $.ajax({
              url:"src/dashboard/index.php",
              type:"POST",
              data:{
                  filtro:'limpar',
              },
              success:function(dados){
                  $("#paginaHome").html(dados);
              }
          })
        })
        
    })
</script>