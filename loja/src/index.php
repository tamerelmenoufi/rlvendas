<?php
    include("../../lib/includes.php");

    /*
    select a.*, b.descricao as situacao_entrega from vendas a left join delivery_status b on a.delivery->>'$.situation' = b.cod where 
                                                a.app = 'delivery' and 
                                                a.cliente = '{$_SESSION['AppCliente']}' and 
                                                a.situacao = 'pago' and a.deletado != '1' order by a.codigo desc
    //*/
    $query = "select 
                    a.*,
                    b.descricao as situacao_entrega,
                    b.tema,
                    c.nome as Cnome,
                    c.telefone as Ctelefone,
                    c.logradouro as Clogradouro,
                    c.numero as Cnumero,
                    c.cep as Ccep,
                    c.complemento as Ccomplemento,
                    c.ponto_referencia as Cponto_referencia,
                    c.bairro as Cbairro 
                    
            from vendas a 
                                
                            left join delivery_status b on a.delivery->>'$.situation' = b.cod 
                            left join clientes c on a.cliente = c.codigo 
            
            where 
                            a.app = 'delivery' and 
                            a.situacao = 'pago' and 
                            a.deletado != '1' 

            order by a.codigo desc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        $delivery = json_decode($d->delivery);

        $end = [
            $d->Clogradouro,
            $d->Cnumero,
            $d->Ccomplemento,
            $d->Cponto_referencia,
            $d->Ccep,
            $d->Cbairro
        ];

        $endereco = [];
        foreach($end as $i => $val){
            if($val){
                $endereco[] = $val;
            }
            
        }
        if($endereco){
            $endereco = implode(", ", $endereco);
        }else{
            $endereco = false;
        }


?>
<div class="card border-<?=$d->tema?> m-3">
    <h5 class="card-header">Pedido #<?=$d->codigo?> (<?=($d->data_finalizacao)?>)</h5>
    <div class="card-body">

        <div class="d-flex justify-content-between">
            <div>Cliente</div>
            <span><?=$d->Cnome?></span>
        </div>

        <div class="d-flex justify-content-between">
            <div>Cliente (Telefone)</div>
            <span><?=$d->Ctelefone?></span>
        </div>

        <div class="d-flex justify-content-between">
            <div><?=$endereco?></div>
        </div>
        <hr>

        <div class="d-flex justify-content-between">
            <div>Valor</div>
            <span>R$ <?=number_format($d->valor, 2,',', false)?></span>
        </div>

        <div class="d-flex justify-content-between">
            <div>Taxa Entrega</div>
            <span>R$ <?=number_format($d->taxa, 2,',', false)?></span>
        </div>

        <div class="d-flex justify-content-between">
            <div>Desconto</div>
            <span>R$ <?=number_format($d->desconto, 2,',', false)?></span>
        </div>

        <div class="d-flex justify-content-between">
            <div>Acrescimo</div>
            <span>R$ <?=number_format($d->acrescimo, 2,',', false)?></span>
        </div>

        <div class="d-flex justify-content-between">
            <div><b>Total</b></div>
            <span><b>R$ <?=number_format(($d->valor + $d->taxa - $d->desconto + $d->acrescimo), 2,',', false)?></b></span>
        </div>
        <?php
        if($delivery->deliveryMan->id){
        ?>
        <div class="d-flex justify-content-between mt-3">
            <div>Entregador</div>
            <span><?=$delivery->deliveryMan->name?></span>
        </div>
        <div class="d-flex justify-content-between">
            <div>Telefone (Entregador)</div>
            <span><?='('.$delivery->deliveryMan->ddd.') '.$delivery->deliveryMan->phone?></span>
        </div>
        <div class="d-flex justify-content-between">
            <div>Código Retirada</div>
            <span><b><?=$delivery->pickupCode?></b></span>
        </div>
        <div class="d-flex justify-content-between">
            <div>Código Retorno</div>
            <span><b><?=$delivery->returnCode?></b></span>
        </div>
        <?php
        }
        ?>
        <div class="d-flex justify-content-start">
            <div style="padding-right:7px;">Situação</div>
            <span><?=(($d->situacao_entrega)?:'Em Produção')?></span>
        </div>
        <div class="d-flex justify-content-start">
            <button pedido="<?=$d->codigo?>" class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Pedido</button>
        </div>
    </div>
</div>
<?php
    }
?>


<script>
    $(function(){
        $("button[pedido]").click(function(){
            pedido = $(this).attr("pedido");
            $.ajax({
                url:"src/pedido.php",
                type:"POST",
                data:{
                    pedido
                },
                success:function(dados){
                    $(".popupPalco").html(dados);
                    $(".popupArea").css("display","block");
                }
            })
        })
    })
</script>