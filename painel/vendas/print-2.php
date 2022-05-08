<?php
    include("../../lib/includes.php");
    include "./conf.php";

    $query = "select a.*, b.mesa as mesa from vendas a left join mesas b on a.mesa = b.codigo where a.codigo = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    $retorno .= $query;

    $retorno .= 'left|YOBOM SORVETES CNPJ - 28856577000119'."\n";
    $retorno .= 'left|Rua Bruxelas, 15, Manaus - AM'."\n";
    $retorno .= "left|PEDIDO: ".str_pad($p->codigo, 5, "0", STR_PAD_LEFT)."  -  Mesa: {$p->mesa}"."\n";
    $retorno .= "left|Pedido em : ".$p->data_pedido."\n";

    $retorno .= "left|Produtos             Vl Uni              Vl Tot"."\n";


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

        $retorno .= "left|{$d->quantidade} X {$pedido->categoria->descricao} - {$pedido->medida->descricao}"."\n";
        $retorno .= "left|{$d->produto_descricao}"."\n";
        $retorno .= "right|".number_format($d->valor_unitario, 2, ',', '.').""."\n";
        $retorno .= "right|".number_format($d->valor_total, 2, ',', '.').""."\n";

        $valor_total = ($valor_total + $d->valor_total);

    }

    $retorno .= "right|Pagar R$ ".number_format($valor_total, 2, ',', '.').""."\n";
    $retorno .= "center|Yobom.com.br - ".date("d/m/Y H:i:s").""."\n";

    //$retorno = GerarPrint($retorno);

    file_put_contents("print/terminal1.txt", $retorno);

?>