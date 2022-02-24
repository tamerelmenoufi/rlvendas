<?php
include "../../lib/includes.php";

$query = "select * from clientes where codigo = '{$_SESSION['ConfCliente']}'";
$result = mysqli_query($con, $query);
$c = mysqli_fetch_object($result);

?>
<style>
    .bar_header{
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:40px;
        padding:0;
        padding-left:20px;
        color:#fff;
        background-color:red;
        font-weight:bold;
    }
</style>
<nav class="navbar bar_header">
  <span class="navbar-brand mb-0 h1">
    <?php
      if($_SESSION['ConfMesa']){
        echo 'PEDIDO '.$_SESSION['ConfMesa'];
      }
    ?>
  </span>
  <span class="navbar-text" style="margin-right:10px;">
  <?php
      if($c->codigo){
        echo 'Cliente: '.$c->nome.' '.$c->telefone;
      }
    ?>
  </span>
</nav>