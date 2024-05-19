<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    $status = [ 
        'p' => ['Aguardando','#a1a1a1'] , 
        'i' => ['Produção','orange'] , 
        'c' => ['Concluído','blue'], 
        'e' => ['Entregue','green']	
    ];
    

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
                            a.codigo = '{$_GET['cod']}'
    ";
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
<div class="card border-<?=$d->tema?>" style="margin:30px;">
    <h5 class="card-header">Pedido #<?=$d->codigo?></h5>
    <div class="card-body">
        <?php
        if($d->app == 'delivery'){
        ?>
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

        <?php
        }

        $q = "select * from vendas_produtos where venda = '{$d->codigo}' and deletado != '1' order by codigo asc";
        $r = mysqli_query($con, $q);
        while($p = mysqli_fetch_object($r)){

            $produto = json_decode($p->produto_json);
            $produtos = [];

            if($produto->produtos){
                foreach($produto->produtos as $i => $v){
                    $produtos[] = $v->descricao;
                }
                $produtos = implode(" e ", $produtos);
            }
            

            $produto = "{$produto->categoria->descricao} {$produto->medida->descricao} {$produtos}<br>";

        ?>
        <div class="d-flex justify-content-between mt-3 mb-3">
            <div><?=$p->quantidade?> x <?=$produto?></div>
            <span style="color:<?=$status[$p->situacao][1]?>; font-weight:bold;"><?=(($status[$p->situacao][0])?:'Aguardando')?></span>
        </div>        
        <?php
        }
        ?>
        <hr>

        <div class="d-flex justify-content-between">
            <div>Valor</div>
            <span>R$ <?=number_format($d->valor, 2,',', false)?></span>
        </div>

        <div class="d-flex justify-content-between">
            <div>Taxa <?=(($d->app == 'delivery')?'Entrega':'Serviço')?></div>
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
        if($d->app == 'delivery'){
        ?>
        <div class="d-flex justify-content-start">
            <div style="padding-right:7px;">Situação</div>
            <span><?=(($d->situacao_entrega)?:'Em Produção')?></span>
        </div>
        <?php
        }
        if($d->situacao == 'pagar' or $d->situacao == 'pago'){
        ?>
        <table class="table table-hover" style="margin-top:30px;">
            <thead>
                <tr>
                    <th>Caixa</th>
                    <th>Forma de Pgamento</th>
                    <th>Atendente</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
        <?php
        $q = "select a.*, b.nome as atendente_nome from vendas_pagamento a left join atendentes b on a.atendente = b.codigo where a.venda = '{$d->codigo}' and a.deletado != '1'";
        $r = mysqli_query($con, $q);
        while($p = mysqli_fetch_object($r)){
        ?>
                <tr>
                    <td><?=$p->caixa?></td>
                    <td><?=$p->forma_pagamento?></td>
                    <td><?=$p->atendente_nome?></td>
                    <td>R$ <?=number_format($p->valor,2,'.',false)?></td>
                </tr>    
        <?php
        }
        ?>
            </tbody>
        </table>
        <?php
        }
        ?>







    </div>
</div>
<?php
    }
?>


<script>
    $(function(){


    })
</script>