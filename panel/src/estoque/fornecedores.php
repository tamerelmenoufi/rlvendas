<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['cod']){
        $_SESSION['cod_lancamento'] = $_POST['lancamento'];
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
    <input campoBusca type="text" class="form-control" value="<?=$_SESSION['usuarioBusca']?>" aria-label="Digite a informação para a busca">
    <button filtro="filtrar" class="btn btn-outline-secondary" type="button">Buscar</button>
    <button filtro="limpar" class="btn btn-outline-danger" type="button">limpar</button>
</div>
