<?php
    include("../../lib/includes.php");
    include "./conf.php";

    $query = "select a.*, b.mesa as mesa from vendas a left join mesas b on a.mesa = b.codigo where a.codigo = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);


$retorno .= '
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <style>
            html{
                height: 100%;
            }
            body {
                min-height: 100%;
            }
            td, th{
                font-size:30px;
            }
        </style>
    </head>
    <body>
';

$quebra = '-----------------------------------------------------------------------------------------------------------------------------------------------------';


$retorno .= '<h1>YOBOM SORVETES CNPJ - 28856577000119</h1>';
$retorno .= '<h2>Rua Bruxelas, 15, Manaus - AM</h2>';
$retorno .= $quebra;
$retorno .= "<h2>PEDIDO: ".str_pad($p->codigo, 5, "0", STR_PAD_LEFT)."  -  Mesa: {$p->mesa}</h2>";
$retorno .= "<h2>Pedido em : ".$p->data_pedido."</h2>";
$retorno .= $quebra;



$retorno .= '<table width="100%" border="0" cellpadding="2" cellspacing="0">
    <thead>
        <tr>
            <th>Produto</th>
            <th>VL Uni (R$)</th>
            <th>Total (R$)</th>
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
        {$d->quantidade} X {$pedido->categoria->descricao} - {$pedido->medida->descricao}<br>
        <i>{$d->produto_descricao}</i>
    </td>
    <td style=\"text-align:right\">
        ".number_format($d->valor_unitario, 2, ',', '.')."
    </td>
    <td style=\"text-align:right\">
        ".number_format($d->valor_total, 2, ',', '.')."
    </td>
</tr>";

    $valor_total = ($valor_total + $d->valor_total);

    }

$retorno .= '<tr>
    <td colspan="4" style="text-align:right">
      <h3>Pagar <b>R$  '.number_format($valor_total, 2, ',', '.').'</h3>
    </td>
</tr>
<tr>
    <td colspan="4" style="text-align:center">
      Yobom.com.br - '.date("d/m/Y H:i:s").'
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