<?php

//Config
$ConfTitulo = 'Vendas';
$UrlScript = 'vendas/';
//Config ----------

function getSituacao()
{
    return [
        '' => 'Não definido',
        '0' => 'Inativo',
        '1' => 'Ativo',
    ];
}

function getSituacaoOptions($situacao)
{
    $list = getSituacao();
    return $list[$situacao];
}