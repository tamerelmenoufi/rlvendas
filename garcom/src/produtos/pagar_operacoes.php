<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'nova_operacao'){

        $query = "insert into vendas_pagamento set venda = '{$_SESSION['AppVenda']}', forma_pagamento = '{$_POST['operacao']}', valor = '{$_POST['valor']}'";
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
                <span
                    class="excluir_operacao"
                    cod="<?=$p->codigo?>"
                    content="Deja realmente excluir a operação <b><?=$p->forma_pagamento?></b> no valor de <b>R$ <?=number_format($p->valor,2,',','.')?></b>"
                >
                    <i class="fa fa-trash text-red"></i>
                </span>
            </td>
        </tr>
        <?php
            $soma_valores = ($soma_valores + $p->valor);
        }
        ?>
        <tr>
            <th align="right">TOTAL</th>
            <th><?=number_format($soma_valores,2,',','.')?></th>
            <th></th>
        </tr>
    </tbody>
</table>

<h5 class="card-title">
    <a pagar opc="dinheiro" class="btn btn-success btn-lg"><i class="fa-solid fa-money-bill-1"></i> Confirmar Pagamento</a>
</h5>


<?php
    }
?>

<script>
    $(function(){

        valor_total = $(".valor_total").attr("valor");
        soma_valores = '<?=$soma_valores?>';
        valor_pendente = (valor_total - soma_valores);

        $(".valor_pendente").attr("valor", valor_pendente);
        $(".valor_pendente").html('R$ ' + valor_pendente.toLocaleString('pt-br', {minimumFractionDigits: 2}));

        $(".UmPagamento").val(valor_pendente);

        if(valor_pendente == 0){
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



        $("a[pagar]").click(function(){
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