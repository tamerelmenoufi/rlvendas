<?php
    include("../../lib/includes.php");
?>

<div class="row">
    Dados da cozenha (Produção de Pizzas)
<?php
    $query = "select * from vendas_produtos where situacao = 'p' order by data asc";
    $result = mysqli_query($con, $query);

    while($d = mysqli_fetch_object($result)){
?>

<?php
    }
?>
