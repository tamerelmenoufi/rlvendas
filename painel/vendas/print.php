<?php
    include("../../lib/includes.php");
    include "./conf.php";
?>
<table width="100%" border="0" cellpadding="2" cellspacing="0">
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
    $query = "select * from vendas_produtos where venda = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){


        $pedido = json_decode($d->produto_json);
        $sabores = false;
        //print_r($pedido)
        $ListaPedido = [];
        for($i=0; $i < count($pedido->produtos); $i++){
            $ListaPedido[] = $pedido->produtos[$i]->descricao;
        }
        if($ListaPedido) $sabores = implode(', ', $ListaPedido);

?>
<tr>
    <td>
        <?=$pedido->categoria->descricao?> - <?=$pedido->medida->descricao?><br>
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
<tr>
    <td colspan="4">
        <h3>Pagar <b>R$  <?= number_format($valor_total, 2, ',', '.') ?></h3>
    </td>
</tr>
</tbody>
</table>