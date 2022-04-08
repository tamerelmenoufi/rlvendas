<?php

function sis_logs($tabela, $codigo, $query, $operacao = null)
{
    global $con;
    $usuario = $_SESSION['usuario']['codigo'];
    $operacao = $operacao ?: strtoupper(trim(explode(' ', $query)[0]));
    $query = mysqli_real_escape_string($con, $query);
    $data = date("Y-m-d H:i:s");

    $query_log = "INSERT INTO sis_logs "
        . "SET usuario = '{$usuario}', registro = '{$codigo}', operacao = '{$operacao}', query = '{$query}', "
        . "tabela = '{$tabela}', data = '{$data}'";

    mysqli_query($con, $query_log);
}

function exclusao($tabela, $codigo, $fisica = false)
{
    global $con;
    if ($fisica) {
        $query = "DELETE FROM {$tabela} WHERE codigo = '{$codigo}'";
    } else {
        $query = "UPDATE {$tabela} SET deletado = '1' WHERE codigo = '{$codigo}'";
    }

    if (mysqli_query($con, $query)) {
        sis_logs($codigo, $query, $tabela, 'DELETE');
        return true;
    } else {
        return false;
    }
}

function ListaLogs($tabela, $registro){
    global $con;
    $Query = [];
    $query = "select a.*, b.nome from sis_logs a left join usuarios b on a.usuario=b.codigo where a.tabela = '{$tabela}' and a.registro = '{$registro}' order by a.codigo asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

        switch($d->operacao){

            case 'INSERT':{
                $Query[] = [$d->data, $d->operacao, $d->nome, InsertQuery($d->query)];
                break;
            }
            case 'UPDATE':{
                $Query[] = [$d->data, $d->operacao, $d->nome, UpdateQuery($d->query)];
                break;
            }

        }

    }
    return $Query;
}

function InsertQuery($query){
    list($l, $d) = explode("SET", $query);
    $d = str_replace("=","=>", $d);
    eval("\$r = [{$d}];");
    return $r;
}

function UpdateQuery($query){
    list($l, $d) = explode("SET", $query);
    list($d, $l) = explode("WHERE", $d);
    $d = str_replace("=","=>", $d);
    eval("\$r = [{$d}];");
    return $r;

}


function VerificarVendaApp(){
    global $SESSION;

    $r = mysqli_query($con, "select * from vendas where /*codigo = '{$_SESSION['AppVenda']}' and*/ situacao = 'producao' and deletado != '1'  ");
    $n = mysqli_num_rows($r);

    if(!$n){

        mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', data_pedido = NOW(), situacao = 'producao'");
        $_SESSION['AppVenda'] = mysqli_insert_id($con);

        //$_SESSION = [];
        // header("location:./?s=1");
        echo "<script>window.localStorage.addItem('AppPedido','{$_SESSION['AppVenda']}');</script>";
        //exit();
    }else if(!$_SESSION['AppVenda']){
        $_SESSION['AppVenda'] = mysqli_fetch_object($r)->codigo;
    }


}