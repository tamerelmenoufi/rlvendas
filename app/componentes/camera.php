<?php
    include("../../lib/includes.php");

    if($_POST['acao'] == 'Mesa'){
        $query = "SELECT * FROM mesas WHERE md5(mesa) = '{$_POST['mesa']}' and situacao = '1' and deletado != '1'";
        $result = mysqli_query($con, $query);
        $m = mysqli_fetch_object($result);
        echo $m->codigo;
        mysqli_query("UPDATE mesas SET blq = '1' WHERE codigo = '{$d->codigo}'");
        exit();
    }

    $mesas = [];
    $query = "SELECT * FROM mesas a WHERE a.situacao = '1' AND a.deletado != '1' and blq != '1'";
    $result = mysqli_query($con, $query);
    while($m = mysqli_fetch_object($result)){
            $mesas[] = "https://app.yobom.com.br/?".md5($m->mesa);
    }

    if($_SESSION['AppCliente'] && $_SESSION['AppPedido']){
        /////////////////INCLUIR O REGISTRO DO PEDIDO//////////////////////
        $query = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' AND situacao = 'producao'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result)) {
            //$queryInsert = "SELECT codigo FROM vendas WHERE cliente = '{$_SESSION['AppCliente']}' AND mesa = '{$_SESSION['AppPedido']}' AND deletado != '1' LIMIT 1";
            list($codigo) = mysqli_fetch_row(mysqli_query($con, $query));
            $_SESSION['AppVenda'] = $codigo;
        } else {
            mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', data_pedido = NOW(), situacao = 'producao'");
            $_SESSION['AppVenda'] = mysqli_insert_id($con);
        }
        /////////////////////////////////////////////////////////////////
    }


?>
<style>
    #videoCaptura{
        position:fixed;
        top:0px;
        left:0px;
        width:100%;
        height: 100%;
        margin:0;
        padding:0;
        flex:1;
    }
</style>
    <iframe
            name="videoCaptura"
            id="videoCaptura"
            src="../lib/vendor/camera/camera.php?<?=$md5?>"
            frameborder="0"
            marginheight="0"
            marginwidth="0"
    >
    </iframe>

    <script>
        function LeituraCamera(content){

            m = ['<?=@implode("','",$mesas)?>'];

            if(content && $.inArray( content, m ) != -1){

                codMesa = content.split('?');

                $.ajax({
                    url:"componentes/camera.php",
                    type:"POST",
                    data:{
                        acao: 'Mesa',
                        mesa: codMesa[1]
                    },
                    success:function(dados_mesa){

                        window.localStorage.setItem('AppPedido', dados_mesa);

                        $.ajax({
                            url:"src/home/index.php",
                            data:{
                                pedido: dados_mesa,
                            },
                            success:function(dados){
                                $(".ms_corpo").html(dados);
                                PageClose();
                            }
                        });

                    }

                });
/*
                $.ajax({
                    url:"home/index.php?mesa="+mesa,
                    success:function(dados){
                        $("#body").html(dados);
                    }
                });
//*/
            }else{
                $.alert('MESA BLOQUEADO, EM USO!');
                PageClose();
            }


            //document.getElementById('DadosCaptura').innerHTML = 'Adicionado pela função: ' + content;
            //AppMesa = window.localStorage.getItem('AppMesa');
            //window.localStorage.setItem('AppMesa', content);
            //var valor = window.parent.videoCaptura.document.getElementById('campoTeste').value;
        }
    </script>