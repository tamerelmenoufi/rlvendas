<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");
?>

<table class="table" style="margin-top:20px;">
    <thead>
        <tr>
            <th>Produto</th>
            <th>valor Unitário</th>
            <th>Quantidade</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
<?php
    $query = "select * from vendas_produtos where venda = '{$_GET['cod']}' and deletado != '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){


        $pedido = json_decode($d->produto_json);
        $sabores = false;
        // print_r($pedido);
        $ListaPedido = [];
        for($i=0; $i < count($pedido->produtos); $i++){
            $ListaPedido[] = $pedido->produtos[$i]->descricao;
        }
        if($ListaPedido) $sabores = implode(', ', $ListaPedido);

        $Prod = [];
        foreach($pedido->produtos as $ind => $prod){
            $Prod[] = $prod->descricao;
        }
        $Prod = (($Prod)?implode(' ',$Prod):false);
?>

<tr>
    <td>
        <?=$pedido->categoria->descricao?> <?=$Prod?> - <?=$pedido->medida->descricao?><br>
        <?= $d->produto_descricao?>
    </td>
    <td>
        R$ <?= number_format($d->valor_unitario, 2, ',', '.') ?>
    </td>
    <td>
        <?=$d->quantidade?>
    </td>
    <td>
        R$ <?= number_format($d->valor_total, 2, ',', '.') ?>
    </td>
</tr>
<?php

    $valor_total = ($valor_total + $d->valor_total);

    }




    $q = "select
                a.*,
                b.nome as atendente
            from vendas_pagamento a
                left join atendentes b on a.atendente = b.codigo
            where a.venda = '{$_GET['cod']}' and a.deletado != '1'";
    $r = mysqli_query($con, $q);

    if(mysqli_num_rows($r)){
?>
<tr>
    <td colspan="4">
        <h5>Esquema de pagamento</h5>
    <table class="table">
    <thead>
        <tr>
            <th>Atendente</th>
            <th>Operação</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while($p = mysqli_fetch_object($r)){
        ?>
        <tr>
            <td><?=$p->atendente?></td>
            <td><?=$p->forma_pagamento?></td>
            <td><?=$p->valor?></td>
        </tr>
        <?php
            $soma_valores = ($soma_valores + $p->valor);
        }
        ?>
        <tr>
            <th align="right">TOTAL</th>
            <th><?=number_format($soma_valores,2,',','.')?></th>
        </tr>
    </tbody>
    </table>
    </td>
</tr>
<?php
    }
?>

</tbody>
</table>

<h3>Pagar <b>R$  <?= number_format($valor_total, 2, ',', '.') ?></h3>