<?php
    include("../../lib/includes.php");
    include "./conf.php";

    $query = "select a.*, b.mesa as mesa from vendas a left join mesas b on a.mesa = b.codigo where a.codigo = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    // $retorno .= $query;
    //$tipo, $largura, $altura, $alinhamento, $registro)

    $retorno .= 'txt|2|1|left|YOBOM SORVETES CNPJ - 28856577000119'."\n";
    $retorno .= 'txt|1|2|left|Rua Bruxelas, 15, Manaus - AM'."\n";
    $retorno .= "txt|1|2|left|PEDIDO: ".str_pad($p->codigo, 5, "0", STR_PAD_LEFT)."  -  Mesa: {$p->mesa}"."\n";
    $retorno .= "txt|1|2|left|Pedido em : ".$p->data_pedido."\n";

    $retorno .= "txt|1|1|left|Produtos             Vl Uni              Vl Tot"."\n";


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

        $retorno .= "txt|1|1|left|{$d->quantidade} X {$pedido->categoria->descricao} - {$pedido->medida->descricao}"."\n";
        if($d->produto_descricao){
        $retorno .= "txt|1|1|left|    {$d->produto_descricao}"."\n";
        }
        $retorno .= "txt|1|1|right|".str_pad(number_format($d->valor_unitario, 2, ',', '.') , 6 , ' ' , STR_PAD_LEFT)."              ".str_pad(number_format($d->valor_total, 2, ',', '.') , 6 , ' ' , STR_PAD_LEFT)."\n";
        //$retorno .= "txt|1|1|left|".number_format($d->valor_total, 2, ',', '.').""."\n";

        $valor_total = ($valor_total + $d->valor_total);

    }

    $retorno .= "\ntxt|1|2|right|Pagar R$ ".number_format($valor_total, 2, ',', '.').""."\n\n";
    $retorno .= "txt|1|1|center|Yobom.com.br - ".date("d/m/Y H:i:s").""."\n";

    //$retorno = GerarPrint($retorno);

    file_put_contents("print/terminal1.txt", $retorno);

?>