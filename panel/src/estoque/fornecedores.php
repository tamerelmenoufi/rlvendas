<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['cod']){
        $_SESSION['cod_lancamento'] = $_POST['lancamento'];
    }

    if($_POST['filtro'] == 'filtrar'){
        $_SESSION['textoBusca'] = $_POST['campo'];
      }elseif($_POST['filtro']){
        $_SESSION['textoBusca'] = false;
      }
  
      if($_SESSION['textoBusca']){
        $cpf = str_replace( ['.','-','/'], false, $_SESSION['textoBusca']);
        $where = " and nome_razao_social like '%{$_SESSION['textoBusca']}%' or REPLACE( REPLACE( REPLACE( cpf_cnpj, '/', '' ), '.', '' ), '-', '' ) = '{$cpf}' ";
      }

    $query = "select * from lancamentos where codigo = '{$_SESSION['cod_lancamento']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Lançamento <?=$d->numero?></h4>
<h6>Selecione um Fornecedor</h6>
<div class="input-group">
<label class="input-group-text" for="inputGroupFile01">Buscar por </label>
    <input textoBusca type="text" class="form-control" value="<?=$_SESSION['textoBusca']?>" aria-label="Digite a informação para a busca">
    <button filtro="filtrar" class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
    <button filtro="limpar" class="btn btn-outline-danger" type="button"><i class="fa-solid fa-eraser"></i></button>
    <button novo class="btn btn-outline-primary" type="button"><i class="fa-solid fa-pen-to-square"></i></button>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome/Razão Social</th>
            <th>CPF</th>
        </tr>
    </thead>
    <tbody>
<?php
    echo $query = "select * from fornecedores {$where} order by nome_razao_social limit 100";
    $result = sisLog($query);
    while($d = mysqli_fetch_object($result)){
?> 
        <tr>
            <td><?=$d->nome_razao_social?></td>
            <td><?=$d->cpf_cnpj?></td>
            <td>
                <i class="fa-regular fa-square-plus" style="cursor:pointer"></i>
            </td>
        </tr>
<?php
    }
?>
    </tbody>
</table>


<script>
    $(function(){

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          campo = $("input[textoBusca]").val();
          $.ajax({
              url:"src/estoque/fornecedores.php",
              type:"POST",
              data:{
                  filtro,
                  campo
              },
              success:function(dados){
                $(".LateralDireita").html(dados);
              }
          })
        })

    })
</script>