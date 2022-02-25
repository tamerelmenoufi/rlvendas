<?php
    include("../../../../lib/includes.php");


    $query = "select * from vendas where vd_cliente = '{$_SESSION['ms_cli_codigo']}' and vd_situacao = '0'";
    $result = mysql_query($query);

    //if(mysql_num_rows($result)){

        if(mysql_num_rows($result)){
            $d = mysql_fetch_object($result);
            $cod_venda = $d->codigo;
        }else{
            mysql_query("insert into vendas set vd_cliente = '{$_SESSION['ms_cli_codigo']}'");
            $cod_venda = mysql_insert_id();
        }

        if($_GET['codigo']){

            $chave = "{$_SESSION['ms_cli_codigo']}-{$cod_venda}-{$_GET['codigo']}";


            $query = "select * from vendas_produtos where chave = '{$chave}'";
            $result = mysql_query($query);
            $n_produtos = mysql_num_rows($result);
            if($n_produtos){

                $d = mysql_fetch_object($result);

                switch($_GET['opc']){
                    case 'mais':{
                        mysql_query("update vendas_produtos set vp_quantidade = (vp_quantidade + 1) where chave = '{$chave}'");
                        break;
                    }
                    case 'menos':{
                        if($d->vp_quantidade == 1){
                            mysql_query("delete from vendas_produtos where chave = '{$chave}'");
                        }else{
                            mysql_query("update vendas_produtos set vp_quantidade = (vp_quantidade - 1) where chave = '{$chave}'");
                        }
                        break;
                    }
                }

            }else{
                $p = mysql_fetch_object(mysql_query("select * from produtos where codigo = '{$_GET['codigo']}'"));
                mysql_query("insert into vendas_produtos set
                                                            vp_quantidade = '1',
                                                            vp_venda = '{$cod_venda}',
                                                            vp_cliente = '{$_SESSION['ms_cli_codigo']}',
                                                            chave = '{$chave}',
                                                            vp_produto = '{$p->codigo}',
                                                            vp_valor_unitario = '{$p->prd_valor}',
                                                            vp_unidade = '{$d->prd_unidade}',
                                                            vp_data = NOW()");
                //$cod_venda = mysql_insert_id();
            }



        }

        $q = 0;
        $val = 0;
        //$q = "select count(*) from vendas_produtos where vp_venda = '{$cod_venda}'";
        $q = "select count(*) as qt, sum(vp_valor_unitario*vp_quantidade) as val from vendas_produtos where vp_venda = '{$cod_venda}'";
        $qr = mysql_query($q);
        if(mysql_num_rows($qr)){
            list($qt, $val) = mysql_fetch_row($qr);
        }

        echo $qt.'|'.$val;

    //}
