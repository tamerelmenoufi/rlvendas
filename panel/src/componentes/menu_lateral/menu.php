<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");
?>
<style>
  a[url]{
    cursor:pointer;
  }
</style>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <img src="img/logo.png" style="height:60px;" alt="">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <h5>Yobom - Painel</h5>
  
    <div class="row mb-1">
      <div class="col">
        <a url="src/dashboard/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line col-1"></i> <span class="col-11">Dashboard</span>
        </a>
      </div>
    </div>

    <hr>

    <h5>Vendas</h5>

    <div class="row mb-1">
      <div class="col">
        <a url="src/vendas/index.php?filtro=garcom" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Mesa (Garçom)</span>
        </a>
      </div>
    </div>   
    <div class="row mb-1">
      <div class="col">
        <a url="src/vendas/index.php?filtro=cliente" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Mesa (Cliente)</span>
        </a>
      </div>
    </div>    
    <div class="row mb-1">
      <div class="col">
        <a url="src/vendas/index.php?filtro=viagem" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Viagem</span>
        </a>
      </div>
    </div>   
    <div class="row mb-1">
      <div class="col">
        <a url="src/vendas/index.php?filtro=delivery" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Delivery</span>
        </a>
      </div>
    </div>   

    <hr>

    <h5>Produtos Produção</h5>

    <div class="row mb-1">
      <div class="col">
        <a url="src/categorias/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Categorias</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/categoria_medidas/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Medidas</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/mesas/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Mesas</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/pagamentos/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Pagamentos</span>
        </a>
      </div>
    </div>


    <hr>

    <h5>Controle de Estoque</h5>

    <div class="row mb-1">
      <div class="col">
        <a url="src/estoque/lancamentos.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Lancamento</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/estoque/saidas.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Saídas</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/unidades_medida/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Unidades de Medida</span>
        </a>
      </div>
    </div>    


    <hr>

    <h5>Configurações</h5>

    <div class="row mb-1">
      <div class="col">
        <a url="src/usuarios/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Usuários do Sistema</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/atendentes/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Atendentes</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/clientes/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Clientes</span>
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