<?php

function dataBr($dt){
    list($d, $h) = explode(" ",$dt);
    list($y,$m,$d) = explode("-",$d);
    $data = false;
    if($y && $m && $d){
        $data = "{$d}/{$m}/$y".(($h)?" {$h}":false);
    }
    return $data;
}

function dataMysql($dt){
    list($d, $h) = explode(" ",$dt);
    list($d,$m,$y) = explode("/",$d);
    $data = false;
    if($y && $m && $d){
        $data = "{$y}-{$m}-$d".(($h)?" {$h}":false);
    }
    return $data;
}

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


function VerificarVendaApp($app = 'garcom'){
    global $SESSION;
    global $con;



    if($app == 'delivery'){
        $r = mysqli_query($con, "SELECT * FROM vendas WHERE app = '{$app}' and cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND situacao not in ('pago','pagar') AND deletado != '1' LIMIT 1");
    }else{
        $r = mysqli_query($con, "SELECT * FROM vendas WHERE /*app = '{$app}' and cliente = '{$_SESSION['AppCliente']}' AND*/app != 'delivery' and mesa = '{$_SESSION['AppPedido']}' AND situacao not in ('pago','pagar') AND deletado != '1' LIMIT 1");
    }

    //$r = mysqli_query($con, "SELECT * FROM vendas WHERE app = '{$app}' and cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND situacao not in ('pago','pagar') AND deletado != '1' LIMIT 1");

    $n = mysqli_num_rows($r);

    if(!$n){

        $q = "INSERT INTO vendas SET 
                                        app = '{$app}', 
                                        cliente = '{$_SESSION['AppCliente']}', 
                                        atendente = '{$_SESSION['AppGarcom']}',
                                        mesa = '{$_SESSION['AppPedido']}', 
                                        data_pedido = NOW(), 
                                        situacao = 'producao'";

        mysqli_query($con, $q);
        // mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', data_pedido = NOW(),  situacao not in ('pago','pagar')");
        $_SESSION['AppVenda'] = mysqli_insert_id($con);
        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_SESSION['AppVenda']
            ]
        );

        //$_SESSION = [];
        // header("location:./?s=1");
        echo "<script>window.localStorage.setItem('AppVenda','{$_SESSION['AppVenda']}');</script>";
        //echo "<h1>TESTE 1</h1>";
        //exit();
    }else if(!$_SESSION['AppVenda']){
        $_SESSION['AppVenda'] = mysqli_fetch_object($r)->codigo;
        $q = "UPDATE vendas SET 
                                        app = '{$app}', 
                                        cliente = '{$_SESSION['AppCliente']}', 
                                        atendente = '{$_SESSION['AppGarcom']}',
                                        mesa = '{$_SESSION['AppPedido']}', 
                                        data_pedido = NOW() where codigo = '{$_SESSION['AppVenda']}'";
        mysqli_query($con, $q);
        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_SESSION['AppVenda']
            ]
        );
                                        
        echo "<script>window.localStorage.setItem('AppVenda','{$_SESSION['AppVenda']}');</script>";
        //echo "<h1>TESTE 2</h1>";
    }else{
        //echo "<h1>TESTE 3</h1>";
        $_SESSION['AppVenda'] = mysqli_fetch_object($r)->codigo;
        $q = "UPDATE vendas SET 
                cliente = '{$_SESSION['AppCliente']}', 
                atendente = '{$_SESSION['AppGarcom']}',
                mesa = '{$_SESSION['AppPedido']}', 
                data_pedido = NOW() where codigo = '{$_SESSION['AppVenda']}'";
        mysqli_query($con, $q);
        echo "<script>window.localStorage.setItem('AppVenda','{$_SESSION['AppVenda']}');</script>";

        // sisLog(
        //     [
        //         'query' => $q,
        //         'file' => $_SERVER["PHP_SELF"],
        //         'sessao' => $_SESSION,
        //         'registro' => $_SESSION['AppVenda']
        //     ]
        // );

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
        printf( '<small>'.$dataIni.'</small> - %dh ', $horas );
    }

    if($minutos){
        // echo $minutos."min ";
        printf( '%dmin', $minutos );
    }

    // printf( '%d:%d', $diferenca/3600, $diferenca/60%60 );

}


function sisLog($d){

    global $con;

    $query = addslashes($d['query']);
    $file = $d['file'];
    $sessao = json_encode($d['sessao']);
    $registro = $d['registro'];
    $p = explode(" ",$query);
    $operacao = strtoupper(trim($p[0]));
    if(strtolower(trim($p[0])) == 'insert'){
        $tabela =  strtolower(trim($p[2]));
    }
    if(strtolower(trim($p[0])) == 'update'){
        $tabela =  strtolower(trim($p[1]));
    }

    mysqli_query($con, "
        INSERT INTO sisLog set 
                                file = '{$file}',
                                tabela = '{$tabela}',
                                operacao = '{$operacao}',
                                registro = '{$registro}',
                                sessao = '{$sessao}',
                                query = '{$query}',
                                data = NOW()
    ");
    

}