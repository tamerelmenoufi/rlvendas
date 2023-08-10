<?php
    include("../../../lib/includes.php");

    $Status = [
        'pending' => '<span style="color:red">Pendente</span>',
        'approved' => '<span style="color:green">Aprovado</span>',
    ];

    $PIX = new MercadoPago;
    $retorno = $PIX->ObterPagamento($_POST['id']);
    $operadora_retorno = $retorno;
    $retorno = json_decode($retorno);

    echo "<p>".date("d/m/Y H:i:s")."<br>Pagamento: ".$Status[$retorno->status]."</p>";

    if($retorno->status == 'approved'){

        $v = mysqli_fetch_object(mysqli_query($con, "select codigo from vendas where operadora_id = '{$_POST['id']}'"));


        $codigos = [];
        $query = "SELECT * FROM vendas_produtos WHERE venda = '$v->codigo' and situacao = 'n'";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
            $codigos[] = $d->codigo;
        }
        $codigos = implode(",", $codigos);

        $ordem = strtotime("now");

        $query = "UPDATE vendas_produtos SET situacao = 'p', ordem = '{$ordem}', pago = '1' WHERE codigo in ({$codigos})";
        mysqli_query($con, $query);

        mysqli_query($con, "update vendas set
                            operadora_situacao = '{$retorno->status}',
                            operadora_retorno = '{$operadora_retorno}',
                            situacao = 'preparo'
                        where codigo = '{$v->codigo}'
                    ");

        list($valorPago) = mysqli_fetch_row(mysqli_query($con, "select sum(valor) from vendas_pagamento where venda = '{$v->codigo}' and operadora_situacao = 'approved'"));

        mysqli_query($con, "INSERT INTO vendas_pagamento set
                            venda = '{$v->codigo}',
                            data = NOW(),
                            forma_pagamento = 'pix',
                            valor = '($v->total - $valorPago)',
                            operadora = 'mercado_pago',
                            operadora_situacao = 'approved'
                    ");
        
    }

?>
<style>
    .status_pagamento{
        width:100%;
        text-align:center;
    }
</style>
<script>
    $(function(){
        <?php
        if($retorno->status != 'approved'){
        ?>
        setTimeout(() => {
            $.ajax({
                url:"src/produtos/pagar_pix_verificar.php",
                type:"POST",
                data:{
                    id:'<?=$_POST['id']?>'
                },
                success:function(dados){
                    $(".status_pagamento").html(dados)
                }
            });
        }, 5000);
        <?php
        }else{
        ?>
            $.alert('Pagamento Confirmado.<br>Seu pedido está em preparo!')
            PageClose();
        <?php
        }
        ?>
    })
</script>