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

function getSituacaoOptions($situacao)
{
    $list = getSituacao();
    return $list[$situacao];
}