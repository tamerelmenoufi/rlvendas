<?php

//Config
$ConfTitulo = 'Vendas';
$UrlScript = 'vendas/';
//Config ----------

function getSituacao()
{
    return [
        'producao' => 'Produção',
        'preparo' => 'Preparo',
        'pagar' => 'Pagar',
        'pago' => 'Pago',
        'cancelado' => 'Cancelado',

    ];
}

function produtos_preparo($v){
    global $con;
    $query = "select situacao from vendas_produtos where venda = '{$v}' and situacao != 'n'";
    $result = mysqli_query($con, $query);
    $n = mysqli_num_rows($result);
    $c = 0;
    while($d = mysqli_fetch_object($result)){
        if($d->situacao == 'c') $c++;
    }
    return number_format($c*100/$n,0,false,false);
}


function getSituacaoOptions($situacao, $venda)
{
    if($situacao == 'preparo'){
        $list = getSituacao();
        $pct = produtos_preparo($venda);
        $retorno = "<div class=\"progress\">
                        <div
                            class=\"progress-bar bg-success\"
                            role=\"progressbar\"
                            style=\"width: {$pct}%\"
                            aria-valuenow=\"{$pct}\"
                            aria-valuemin=\"0\"
                            aria-valuemax=\"100\"
                        >{$list[$situacao]}</div>
                    </div>";
    }else{
        $list = getSituacao();
        if($list[$situacao] == 'Pagar'){
            $retorno = "<span acao='pago' cod='{$venda}' class='badge badge-danger'>{$list[$situacao]}</span>";
        }else{
            $retorno = "<span class='badge badge-danger'>{$list[$situacao]}</span>";
        }

    }
    return $retorno;
}

function GerarPDF($d){

    $dadosParaEnviar = http_build_query(
        array(
            'tipo' => 'pdf',
            'html' => ($d),
            'width' => 400,
        )
    );
    $opcoes = array('http' =>
           array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $dadosParaEnviar
        )
    );
    $contexto = stream_context_create($opcoes);
    $result   = file_get_contents('http://html2img.mohatron.com/gerar.php', false, $contexto);

    return $result;

}