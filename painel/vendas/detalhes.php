<?php
    include("../../lib/includes.php");
    include "./conf.php";
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
    $query = "select * from vendas_produtos where venda = '{$_GET['cod']}'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){


        $pedido = json_decode($d->produto_json);
        $sabores = false;
        print_r($pedido);
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
        <?=$Prod?> <?=$pedido->categoria->descricao?> - <?=$pedido->medida->descricao?><br>
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
?>
</tbody>
</table>

<h3>Pagar <b>R$  <?= number_format($valor_total, 2, ',', '.') ?></h3>