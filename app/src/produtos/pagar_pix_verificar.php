<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/cegonha/painel/lib/includes.php");

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
        //Aqui entra a solicitação da Bee
        // e tbm a mudança de status para pedido em produção
        mysqli_query($con, "update vendas set
                            operadora_situacao = '{$retorno->status}',
                            operadora_retorno = '{$operadora_retorno}',
                            situacao = 'c'
                        where operadora_id = '{$_POST['id']}'
                    ");

        $_SESSION['CodVenda'] = false;
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
                url:"src/pagar_pix_verificar.php?convidado=<?=$_SESSION['convidado']?>",
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
            window.location.href='https://cegonha.project.tec.br/index.php?c=<?=md5($_SESSION['convidado'])?>';
        <?php
        }
        ?>
    })
</script>