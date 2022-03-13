<?php
//Config
$ConfTitulo = 'Usuários';
$UrlScript = 'usuarios/';
//Config ----------

function getSituacao()
{
    return [
        '0' => 'Inativo',
        '1' => 'Ativo',
    ];
}

function getSituacaoOptions($situacao)
{
    $list = getSituacao();
    return $list[$situacao];
}