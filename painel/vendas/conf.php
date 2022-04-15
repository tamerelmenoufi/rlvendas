<?php

//Config
$ConfTitulo = 'Vendas';
$UrlScript = 'vendas/';
//Config ----------

function getSituacao()
{
    return [
        'producao' => 'Produção',
        'pagar' => 'Pagar',
        'pago' => 'Pago',
        'cancelado' => 'Cancelado',

    ];
}

function produtos_preparo($v){
    global $con;
    $query = "select situacao from vendas_produtos where venda = '{$v}'";
    $result = mysqli_query($con, $query);
    $n = mysqli_num_rows($con);
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
        $retorno = "<span class='badge badge-danger'>{$list[$situacao]}</span>";

    }
    return $retorno;
}