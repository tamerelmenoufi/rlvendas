<?php
    include("../../lib/includes.php");
    include "./conf.php";

$retorno .= '
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body{
                font-size:20px;
            }
        </style>
    </head>
    <body>
';

$retorno .= '<table width="100%" border="0" cellpadding="2" cellspacing="0">
    <thead>
        <tr>
            <th>Produto</th>
            <th>valor Unitário</th>
            <th>Quantidade</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>';

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


$retorno .= "<tr>
    <td>
        {$pedido->categoria->descricao} - {$pedido->medida->descricao}<br>
        {$d->produto_descricao}
    </td>
    <td>
        R$ ".number_format($d->valor_unitario, 2, ',', '.')."
    </td>
    <td>
        {$d->quantidade}
    </td>
    <td>
        R$ ".number_format($d->valor_total, 2, ',', '.')."
    </td>
</tr>";

    $valor_total = ($valor_total + $d->valor_total);

    }

$retorno .= '<tr>
    <td colspan="4">
        <h3>Pagar <b>R$  '.number_format($valor_total, 2, ',', '.').'</h3>
    </td>
</tr>
</tbody>
</table>';


$retorno .= '
    </body>
    </html>
';

echo GerarPDF($retorno);

?>