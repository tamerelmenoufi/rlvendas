<?php
    include("../../lib/includes.php");
?>
<div style="position:fixed; left:0px; top:0px; width:100%; border:solid 1px red;">
    <div class="row">
        <div class="col">
            Dados da cozenha (Produção de Pizzas)
        </div>

    <?php
        $query = "select * from vendas_produtos where situacao = 'p' order by data asc";
        $result = mysqli_query($con, $query);

        while($d = mysqli_fetch_object($result)){
    ?>

    <?php
        }
    ?>
    </div>
</div>