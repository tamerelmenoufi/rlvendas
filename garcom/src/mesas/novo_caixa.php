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

        $query = "select
                        (select sum(valor) from vendas_pagamento where caixa = '".($caixa->caixa * 1)."' and forma_pagamento = 'dinheiro') as fisico_calculado,
                        (select sum(valor) from vendas_pagamento where caixa = '".($caixa->caixa * 1)."') as vendas

        ";
        $d = mysqli_fetch_object(mysqli_query($con, $query));
        echo "<br><br><br>";
        echo "Físico do caixa anterior: R$ ".($caixa->fisico_declarado);
        echo "<br>";
        echo "Caixa Físico atual: R$ ".($d->fisico_calculado - ($caixa->fisico_declarado * 1));
        echo "<br>";
        echo "Caixa Vendas Geral: R$ ".$d->vendas;

    ?>

<div class="col">
    <!-- <div class="col-12">Cadastro/Acesso do Cliente</div> -->
    <h4 class="col-12 mb-4">Informe os dados solicitados abaixo:</h4>

    <div class="col-12 mb-3">
        <label for="cpf">Valor em caixa (espécie)</label>
        <input style="text-align:center" type="text" inputmode="numeric" autocomplete="off" class="form-control form-control-lg" id="fisico_declarado">
    </div>
    <div class="col-12 mt-4">
        <button abrirNovoCaixa class="btn btn-primary btn-block btn-lg">Abrir Novo Caixa</button>
        <input type="text" id="fisico_calculado" value="<?=$d->fisico_calculado?>" />
        <input type="text" id="vendas" value="<?=$d->vendas?>" />
    </div>
</div>

</div>

<script>
    $(function(){
        $('#fisico_declarado').maskMoney({ thousands: false, decimal:'.' });

        if(terminal){
            $('#fisico_declarado').keyboard({type:'numpad'});;
        }

        $("button[abrirNovoCaixa]").click(function(){
            fisico_declarado = $("#fisico_declarado").val();
            fisico_calculado = $("#fisico_calculado").val();
            vendas = $("#vendas").val();
            if(fisico_declarado && fisico_calculado && vendas){
                $.confirm({
                    content:`Confirma o fechamento do caixa com o valor de <b>R$ ${fisico_declarado}</b>?`,
                    title:"Fechamento de Caixa",
                    buttons:{
                        'SIM':function(){

                        },
                        'NÃO':function(){

                        }
                    }
                });
            }
        });

    })
</script>