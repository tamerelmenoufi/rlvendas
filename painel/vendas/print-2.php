<?php
    include("../../lib/includes.php");
    include "./conf.php";

    $query = "select a.*, b.mesa as mesa from vendas a left join mesas b on a.mesa = b.codigo where a.codigo = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    // $retorno .= $query;
    //$tipo, $largura, $altura, $alinhamento, $registro)
    list($dt, $hr) = explode(" ",$p->data_pedido);
    list($y, $m, $d) = explode("-",$dt);
    $data_pedido = "{$d}/{$m}/{$y} {$hr}";

    $retorno .= 'txt|2|1|center|YOBOM SORVETES'."\n";
    $retorno .= 'txt|1|1|left|CNPJ - 28.856.577/0001-19'."\n";
    $retorno .= 'txt|1|2|left|Rua Bruxelas, 15, Manaus - AM'."\n";
    $retorno .= "txt|1|2|left|PEDIDO: ".str_pad($p->codigo, 5, "0", STR_PAD_LEFT)."  -  Mesa: {$p->mesa}"."\n";
    $retorno .= "txt|1|1|left|Pedido em : ".$data_pedido."\n\n";

    $retorno .= "txt|1|1|left|Produtos             Vl Uni              Vl Tot"."\n";


    $query = "select * from vendas_produtos where venda = '{$_POST['cod']}' and deletado != '1'";
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

        $retorno .= "txt|1|1|left|{$d->quantidade} X {$pedido->categoria->descricao} {$sabores} - {$pedido->medida->descricao}"."\n";
        if($d->produto_descricao){
        $retorno .= "txt|1|1|left|    {$d->produto_descricao}"."\n";
        }
        $retorno .= "txt|1|1|right|R$ ".
        str_pad(number_format($d->valor_unitario, 2, ',', '.') , 6 , ' ' , STR_PAD_LEFT).
        "         R$ ".
        str_pad(number_format($d->valor_total, 2, ',', '.') , 6 , ' ' , STR_PAD_LEFT)."\n";
        //$retorno .= "txt|1|1|left|".number_format($d->valor_total, 2, ',', '.').""."\n";

        $valor_total = ($valor_total + $d->valor_total);

    }

    $retorno .= "\ntxt|1|1|left|Valor Comanda R$ ".number_format($valor_total, 2, ',', '.').""."\n";
    $retorno .= "\ntxt|1|1|left|Taxa Serviço (Opcional) R$ ".number_format($p->taxa, 2, ',', '.').""."\n";
    $retorno .= "\ntxt|1|1|left|Acrescimo R$ ".number_format($p->acrescimo, 2, ',', '.').""."\n";
    $retorno .= "\ntxt|1|1|left|Desconto R$ ".number_format($p->desconto, 2, ',', '.').""."\n\n";

    $retorno .= "\ntxt|1|2|right|Pagar R$ ".number_format( ($valor_total + $p->taxa + $p->acrescimo - $d->desconto), 2, ',', '.').""."\n\n";

    $retorno .= "qrcode|8|8|center|https://notas.yobom.com.br/?{$p->codigo}";
    $retorno .= "\n\ntxt|1|1|center|".md5($p->codigo).""."\n\n";
    $retorno .= "txt|1|1|center|Yobom.com.br - ".date("d/m/Y H:i:s").""."\n";

    //$retorno = GerarPrint($retorno);

    file_put_contents("print/{$_POST['terminal']}.txt", $retorno);

?>