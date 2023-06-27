<?php
    include("../../../lib/includes.php");
?>
<style>
    .vlrP{
        width:80px;
    }
    .vlrN{
        width:80px;
        color:red;
        background-color:#eee;
        border-radius:5px;
    }
    .botao{
        background-color:#007bff !important;
        color:#ffffff !important;
    }
    .botaoN{
        background-color:#28a745 !important;
        color:#ffffff !important;
    }
    .topo<?=$md5?>{
        position:fixed;
        top:0;
        left:0;
        right:0;
        height:60px;
        background:#fff;
        z-index:1;
        padding-left:80px;
        padding-top:10px;
        font-size:25px;
    }
</style>
<div class="topo<?=$md5?>">
    Novo Caixa
</div>
<div style="padding:10px;">
    <?php

        $caixa = mysqli_fetch_object(mysqli_query("select * from caixa where situacao = '0'"));

        echo $query = "select
                        (select sum(valor) vendas_pagamento where caixa = '{$caixa->caixa}' and forma_pagamento = '{dinheiro}') as fisico_calculado,
                        (select sum(valor) vendas_pagamento where caixa = '{$caixa->caixa}') as vendas

        ";
        $d = mysqli_fetch_object(mysqli_query($query));

        echo "Físico do caixa anterior: R$ ".($caixa->fisico_declarado);
        echo "<br>";
        echo "Caixa Físico atual: R$ ".($d->fisico_calculado - $caixa->fisico_declarado);
        echo "<br>";
        echo "Caixa Vendas Geral: R$ ".$d->vendas;

    ?>
</div>

<script>
    $(function(){


    })
</script>