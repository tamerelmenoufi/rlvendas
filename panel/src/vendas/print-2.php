<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    function DataFormat($dt){
        $dt = substr($dt, 0, -6);
        list($d, $h) = explode("T",$dt);
        list($a, $m, $d) = explode("-",$d);
        return "{$d}/{$m}/{$a} {$h}";
    }

    $query = "select a.*, b.mesa as mesa from vendas a left join mesas b on a.mesa = b.codigo where a.codigo = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    $dados = json_decode($p->nf_json);


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

    if($p->nf_status == 'aprovado'){
        $retorno .= 'txt|1|1|center|Documento Auxiliar da Nota Fiscal de Consumidor Eletronica'."\n";
        $retorno .= 'txt|1|1|center|Não permite aproveitamento de crédito de ICMS'."\n\n";
    }

    $retorno .= "txt|1|1|left|Produtos             Vl Uni              Vl Tot"."\n";


    $query = "select * from vendas_produtos where venda = '{$_POST['cod']}' and deletado != '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){


        $pedido = json_decode($d->produto_json);
        $sabores = false;
        $ListaPedido = [];
        for($i=0; $i < count($pedido->produtos); $i++){
            $ListaPedido[] = $pedido->produtos[$i]->descricao;
        }
        if($ListaPedido) $sabores = implode(', ', $ListaPedido);

        $retorno .= "txt|1|1|left|{$d->quantidade} X {$pedido->categoria->descricao} {$sabores} - {$pedido->medida->descricao}"."\n";
        if($d->produto_descricao){
        $retorno .= "txt|1|1|left|    ".strip_tags($d->produto_descricao)."\n";
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
    $retorno .= "\ntxt|1|1|left|Desconto R$ ".number_format($p->desconto, 2, ',', '.').""."\n";

    $retorno .= "\ntxt|1|2|right|Pagar R$ ".number_format( ($valor_total + $p->taxa + $p->acrescimo - $d->desconto), 2, ',', '.').""."\n";

    if($p->nf_status == 'aprovado'){

    $retorno .= "\n\ntxt|1|1|center|Consulte pela Chave de Acesso em:"."\n";
    $retorno .= "txt|1|1|center|".$dados->NFe->infNFeSupl->urlChave."\n";
    $retorno .= "txt|1|1|center|".$dados->protNFe->infProt->chNFe."\n";

    $retorno .= "\n\ntxt|1|1|center|CONSUMIDOR NÃO IDENTIFICADO"."\n";
    $retorno .= "txt|1|1|center|NFCe n. ".str_pad($dados->NFe->infNFe->ide->nNF, 9, '0', STR_PAD_LEFT)." Série ".str_pad($dados->NFe->infNFe->ide->serie, 3, '0', STR_PAD_LEFT)." ".DataFormat($dados->NFe->infNFe->ide->dhEmi)."\n";
    $retorno .= "txt|1|1|center|Protocolo de Autorização: {$dados->protNFe->infProt->nProt}\n";
    $retorno .= "txt|1|1|center|Data de Autorização: ".DataFormat($dados->protNFe->infProt->dhRecbto)."\n\n";

    $retorno .= "qrcode|8|8|center|{$dados->NFe->infNFeSupl->qrCode}";
    $retorno .= "\n\ntxt|1|1|center|Tributos Incidentes (Lei Federal 12.741/2012)"."\n\n";

    }else{

    $retorno .= "qrcode|8|8|center|https://notas.yobom.com.br/?{$p->codigo}";
    $retorno .= "\n\ntxt|1|1|center|".md5($p->codigo).""."\n\n";
    $retorno .= "\n\ntxt|1|1|center|Lote Caixa: ".($p->caixa).""."\n\n";
    $retorno .= "txt|1|1|center|Yobom.com.br - ".date("d/m/Y H:i:s").""."\n";

    }

    file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/painel/vendas/print/{$_POST['terminal']}.txt", $retorno);

?>