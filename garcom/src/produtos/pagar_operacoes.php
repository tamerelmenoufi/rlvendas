<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'nova_operacao'){

        $query = "insert into vendas_pagamento set
                                                venda = '{$_SESSION['AppVenda']}',
                                                atendente = '{$_SESSION['AppGarcom']}',
                                                forma_pagamento = '{$_POST['operacao']}',
                                                valor = '{$_POST['valor']}',
                                                data = NOW()";
        mysqli_query($con, $query);

    }

    if($_POST['acao'] == 'excluir_operacao'){

        $query = "update vendas_pagamento set deletado = '1' where codigo = '{$_POST['cod']}'";
        mysqli_query($con, $query);

    }


    $q = "select * from vendas_pagamento where venda = '{$_SESSION['AppVenda']}' and deletado != '1'";
    $r = mysqli_query($con, $q);

    if(mysqli_num_rows($r)){
?>
<table class="table">
    <thead>
        <tr>
            <th>Operação</th>
            <th>Valor</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $soma_valores = 0;
        while($p = mysqli_fetch_object($r)){
        ?>
        <tr>
            <td><?=$p->forma_pagamento?></td>
            <td><?=$p->valor?></td>
            <td>
                <?php
                if($p->operadora_situacao != 'approved'){
                ?>
                <span
                    class="excluir_operacao"
                    cod="<?=$p->codigo?>"
                    content="Deja realmente excluir a operação <b><?=$p->forma_pagamento?></b> no valor de <b>R$ <?=number_format($p->valor,2,'.',false)?></b>"
                >
                    <i class="fa fa-trash text-danger"></i>
                </span>
                <?php
                }else{
                ?>
                 <span class="text-success">
                    <i class="fa fa-check text-success"></i> Pago
                </span>               
                <?php
                }
                ?>
            </td>
        </tr>
        <?php
            $soma_valores = ($soma_valores + $p->valor);
        }
        ?>
        <tr>
            <th align="right">TOTAL</th>
            <th><?=number_format($soma_valores,2,'.',false)?></th>
            <th></th>
        </tr>
    </tbody>
</table>

<h5 class="card-title">
    <button pagar opc="dinheiro" class="btn btn-success btn-block btn-lg"><i class="fa-solid fa-money-bill-1"></i> Confirmar Pagamento</button>
</h5>


<?php
    }
?>

<script>
    $(function(){

        Carregando('none');

        valor = $(".valor").attr("valor");
        // console.log("Valor:" + valor)
        taxa = 0;
        acrescimo = 0;
        desconto = 0;
        $("input[calc]").each(function(){
            tipo = $(this).attr("calc");

            // if(tipo == 'TaxaServico'){
            //     if($(this).prop("checked") == true){
            //         taxa = $(this).val();
            //     }else{
            //         taxa = 0;
            //     }
            // }

            if(tipo == 'TaxaServico'){
                taxa = $(this).val();
                // console.log("Taxa:" + taxa)
            }
            if(tipo == 'acrescimo'){
                acrescimo = $(this).val();
                // console.log("Acrescimo:" + acrescimo)
            }
            if(tipo == 'desconto'){
                desconto = $(this).val();
                // console.log("Desconto:" + desconto)
            }

        });

        valor_total = (valor*1 + taxa*1 + acrescimo*1 - desconto*1);
        // console.log("Valor Total:" + valor_total)
        soma_valores = '<?=$soma_valores?>';
        valor_pendente = (valor_total - soma_valores);

        console.log(valor_pendente);

        if(valor_pendente*1 > 0){
            // $("button[pagar]").attr("disabled","disabled");
        } 

        $(".valor_pendente").attr("valor", valor_pendente.toFixed(2));
        $(".valor_pendente").attr("pendente", valor_pendente.toFixed(2));
        // $(".valor_pendente").html('R$ ' + valor_pendente.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        $(".valor_pendente").html('R$ ' + valor_pendente.toFixed(2));

        $(".UmPagamento").val(valor_pendente.toFixed(2));

        if(soma_valores == 0){
            $(".formas_pagamento").css("display","none");
        }else{
            $(".formas_pagamento").css("display","block");
        }

        $(".excluir_operacao").click(function(){
            content = $(this).attr("content");
            cod = $(this).attr("cod");
            $.confirm({
                content,
                buttons:{
                    'Sim':function(){
                        $.ajax({
                            url:"src/produtos/pagar_operacoes.php",
                            type:"POST",
                            data:{
                                cod,
                                acao:'excluir_operacao'
                            },
                            success:function(dados){
                                $("div[pagar_operacoes]").html(dados);
                            }
                        });
                    },
                    'Não':function(){

                    }
                }
            });

        });



        $("button[pagar]").click(function(){
            opc = $(this).attr("opc");
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    //local:'src/produtos/pagar_'+opc+'.php',
                    local:'src/produtos/informativo_pagamento.php',
                    opc,
                },
                success:function(dados){
                    //PageClose();
                    $(".ms_corpo").append(dados);
                }
            });
        });


    })
</script>