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
    global $con;

    $r = mysqli_query($con, "SELECT * FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND situacao not in ('pago','pagar') AND deletado != '1'  ");
    $n = mysqli_num_rows($r);

    if(!$n){

        mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', data_pedido = NOW(),  situacao not in ('pago','pagar')");
        $_SESSION['AppVenda'] = mysqli_insert_id($con);

        //$_SESSION = [];
        // header("location:./?s=1");
        echo "<script>window.localStorage.setItem('AppVenda','{$_SESSION['AppVenda']}');</script>";
        //echo "<h1>TESTE 1</h1>";
        //exit();
    }else if(!$_SESSION['AppVenda']){
        $_SESSION['AppVenda'] = mysqli_fetch_object($r)->codigo;
        echo "<script>window.localStorage.setItem('AppVenda','{$_SESSION['AppVenda']}');</script>";
        //echo "<h1>TESTE 2</h1>";
    }else{
        //echo "<h1>TESTE 3</h1>";
    }


}


function CalcTempo($ini, $fim = false){

    $fim = (($fim)?:date("Y-m-d H:i:s"));
    list($d1,$H1) = explode(" ",$ini);
    list($d2,$H2) = explode(" ",$fim);
    list($a1, $m1, $d1) = explode("-",$d1);
    list($a2, $m2, $d2) = explode("-",$d2);
    list($h1, $i1, $s1) = explode(":",$H1);
    list($h2, $i2, $s2) = explode(":",$H2);

    $entrada = gmmktime(  $h1, $i1, $s1, $m1, $d1, $a1 );
    $saida = gmmktime(  $h2, $i2, $s2, $m2, $d2, $a2 );
    $diferenca = abs( $saida - $entrada );

    $horas = ($diferenca/3600);
    $minutos = ($diferenca/60%60);

    $dataIni = "{$d1}/{$m1}/{$a1} {$h1}:{$i1}";

    if($horas){
        // echo $horas."h ";
        printf( $dataIni.' - %dh ', $horas );
    }

    if($minutos){
        // echo $minutos."min ";
        printf( '%dmin', $minutos );
    }

    // printf( '%d:%d', $diferenca/3600, $diferenca/60%60 );

}