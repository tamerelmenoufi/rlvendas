<?php

    include("../../../../lib/includes.php");

    if($_SERVER["REQUEST_METHOD"] === "POST" and $_POST["opc"] === "concluir"){
        $cod_cliente = $_SESSION["ms_cli_codigo"];
        $forma_pagamento = $_POST["forma_pagamento"];

        $query = "SELECT vp_venda, SUM(vp_valor_unitario * vp_quantidade) AS valor_total FROM vendas v "
        ."INNER JOIN vendas_produtos vp ON vp.vp_venda = v.codigo "
        ."WHERE vp.vp_cliente = '{$cod_cliente}' AND v.vd_situacao = '0'";

        $result = mysql_query($query);
        $d = mysql_fetch_object($result);

        // echo json_encode($d);
        // exit;
        $queryConcluir = "UPDATE vendas SET vd_valor = '{$d->valor_total}', vd_data_venda = NOW(), vd_situacao = '1', vd_forma_pgto = '{$forma_pagamento}' "
        ."WHERE codigo = '{$d->vp_venda}'";

        if(mysql_query($queryConcluir)){
            echo json_encode(["status" => true, "msg" => "Compra finalizada com sucesso"]);
        }else{
            echo json_encode(["status" => false, "msg" => "Error ao salvar", "mysql_error" => mysql_error()]);
        }

        exit;
    }

    ProdutosCarrinho();

    if(count($Carrinho['produto'])){
        foreach($Carrinho['produto'] as $i => $q){
            $Prod[] = $i;
        }
        if($Prod){
            $Prod = implode(", ",$Prod);
        }

    }else{
        $Prod = '0';
    }

    $query = "SELECT * FROM `produtos` where codigo in ($Prod)";
    $result = mysql_query($query);


?>
<style>

.ms_usuario_carrinho_titulo_topo{
        position:fixed;
        left:0;
        top:0;
        width:100%;
        height:60px;
        background:#fff;
        text-align:center;
        color:#777;
        font-size:18px;
        font-weight:bold;
        z-index:10;
        padding:15px;
    }

    .ms_usuario_carrinho_titulo_rodape{
        position:fixed;
        left:0;
        bottom:0;
        width:100%;
        height:300px;
        background:#fff;
        z-index:10;
        padding:0px;
    }

    .ms_usuario_carrinho_titulo_rodape button{
        position:absolute;
        height:50px;
        width:120px;
        color:#fff;
        text-align:center;
        border:0;
        right:5px;
        bottom:5px;
        background:transparent;
        background-image:url("svg/btn_grande.svg");
        background-size:100%;
    }

    .ms_usuario_carrinho_titulo_rodape span{
        position:absolute;
        color:#26AD71;
        text-align:center;
        border:0;
        left:10px;
        bottom:10px;
        font-size:25px;
        font-weight:bold;
    }


    .ms_usuario_carrinho_corpo{
        position:fixed;
        top:60px;
        bottom:360px;
        padding-bottom:5px;
        left:0;
        right:0;
        border:solid 0px red;
        overflow:auto;
    }

    .ms_usuario_carrinho_corpo h3{
        padding-left:10px;
        font-size:20px;
    }

    .ms_card_carrinho_100{
        padding:5px;
    }
    .ms_card_carrinho_100 div{
        position:relative;
        height:120px;
        background-color: #EBF4F1;
        border-radius:10px;
        border-bottom-right-radius:25px;
        float:none;
        text-align:left;
    }
    .ms_card_carrinho_100 h4{
        color:#194B38;
        font-size:18px;
        /*width:251px;*/
        height:auto;
        font-style: normal;
        line-height: 14px;
        margin-top:5px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        padding-bottom:4px;
    }
    .ms_card_carrinho_100 h3{
        color:#4CBB5E;
        font-size:24px;
    }
    .ms_card_carrinho_100 p{
    /*width:251px;*/
    height:auto;
    color:#9C9C9C;
    font-style: normal;
    font-size: 12px;
    line-height: 14px;
    text-align:center;
    margin-top:5px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    }
    .ms_card_carrinho_100 font{
        position:relative;
        color:#9C9C9C;
        font-size:8px;
        font-weight:bold;
    }
    .ms_card_carrinho_100 span{
        position:absolute;
        right:0px;
        bottom:0;
    }
    .ms_card_carrinho_100 text{
        padding-left:10px;
        padding-right:10px;
        color:#9C9C9C;
        font-weight:bold;
        font-size:12px;
    }


    .ms_carrinho_endereco_entrega{
        position:relative;
        width:100%;
    }
    .ms_carrinho_endereco_entrega_card{
        position:relative;
        height:auto;
        background-color:#F1F3F2;
        padding-top:10px;
        padding-left:20px;
        padding-right:10px;
        margin-left:5px;
        margin-right:5px;
        margin-bottom:10px;
        border-radius:7px;
        color:#777777;
        cursor:pointer;
        border: solid 1px #4CBB5E;
        background-color: #F1F3F2;
    }

</style>


<div class="ms_usuario_carrinho_titulo_topo">Carrinho</div>



<div class="ms_usuario_carrinho_corpo">




    <div class="w3-row">
        <?php
    if(mysql_num_rows($result)){
        ?>
        <h3>Lista de Compras</h3>
        <?php
        while ($d =  mysql_fetch_object($result) ) {
        ?>
        <div class="w3-col s12 ms_card_carrinho_100">
            <div div<?=$d->codigo?> class="w3-padding">
                <img
                    atua<?=$md5?>
                    cod="<?=$d->codigo?>"
                    src="<?=$config['url_produtos'].$d->codigo.'/100.png'?>"
                    style="margin-top:15px; width:60px;height:60px;float:left; margin-right:10px;"
                />
                <h4><?=utf8_encode($d->prd_produto)?></h4>
                <p><?= utf8_encode($d->prd_descricao)?></p>

                <h3 h3<?=$d->codigo?>>R$ <?= number_format($d->prd_valor*(($Carrinho['produto'][$d->codigo])?$Carrinho['produto'][$d->codigo]:'1'), 2, ',',',');?><font>/Kg</font></h3>
                <span valor='<?= $d->prd_valor?>' cod="<?=$d->codigo?>">
                    <img incluir<?=$md5?> incluir<?=$d->codigo?> src="svg/botao_mais_right.svg" style="display:<?=(($Carrinho['produto'][$d->codigo])?'none':'inline')?>;" />
                    <img produto_menos<?=$md5?> produto_menos<?=$d->codigo?> src="svg/btn_menos.svg" style="margin-bottom:5px; display:<?=(($Carrinho['produto'][$d->codigo])?'inline':'none')?>;" />
                    <text text<?=$d->codigo?> style="display:<?=(($Carrinho['produto'][$d->codigo])?'inline':'none')?>;"><?=(($Carrinho['produto'][$d->codigo])?$Carrinho['produto'][$d->codigo]:'1')?></text>
                    <img produto_mais<?=$md5?> produto_mais<?=$d->codigo?> src="svg/btn_mais.svg" style="margin-bottom:5px; display:<?=(($Carrinho['produto'][$d->codigo])?'inline':'none')?>;" />
                </span>

            </div>
        </div>

        <?php
        }
    }else{
        ?>
        <div class="w3-text-grey" style="position:absolute; bottom:0; width:100%; border:solid 0px red; flex:1; text-align:center;">
            <p><i class="far fa-sad-tear fa-5x"></i></p>
            <p>CARRINHO NÃO CONTEM COMPRAS REGISTRADAS!</p>
        </div>
        <?php

        exit();
    }
        ?>
    </div>
    <?php
        $select = "select * from clientes_enderecos where cli_codigo = '{$_SESSION['ms_cli_codigo']}' and cli_end_padrao = '1'";
        $result = mysql_query($select);
        $d = mysql_fetch_object($result);
    ?>


</div>



<div class="ms_usuario_carrinho_titulo_rodape">

    <div class="w3-row w3-padding">
       <div class="w3-col s12 ms_carrinho_endereco_entrega">
            <h4>Endereço para Entrega</h4>
        </div>
        <div class="w3-col s12 ms_carrinho_endereco_entrega">
            <div class="ms_carrinho_endereco_entrega_card endereco" tela="<?=$md5?>" cod="<?=$d->codigo?>">

                <?php
                if($d->codigo){
                ?>
                <p local_entrega>
                    <b><?=utf8_encode($d->cli_end_apelido)?></b><br>
                    <?=utf8_encode($d->cli_end_rua)?>
                </p>
                <?php
                }else{
                ?>
                <p style="color:red">Selecione aqui seu endereço</p>
                <?php
                }
                ?>

            </div>
        </div>

        <div class="w3-col s12 ms_carrinho_endereco_entrega">
            <h4>Formas de Pagamentos</h4>
        </div>
        <div class="w3-col s12 ms_carrinho_endereco_entrega">
            <div class="ms_carrinho_endereco_entrega_card pagamento" tela="<?=$md5?>" cod="">
                <p style="color:red">Selecione a forma de pagamento</p>
            </div>
        </div>

    </div>


    <Button concluir<?=$md5?> >Concluir</Button>
    <input type="hidden" id="forma_pagamento<?=$md5?>" />
    <input type="hidden" id="endereco_entrega<?=$md5?>" />

    <span valor_total></span>
</div>

<script>
    $(function(){

        Carregando('none');
        CarrinhoOpc();

        $("Button[concluir<?=$md5?>]").off('click').on('click', function(){
            var cod_venda = $(".ms_carrinho_endereco_entrega_card").attr("cod");
            var forma_pagamento = $(".pagamento").attr("cod");
            var cod_endereco = $(".endereco").attr("cod");

            if(!forma_pagamento){
                $.alert({
                    title : "Aviso",
                    theme: "modern",
                    type: "red",
                    icon: 'fas fa-exclamation-triangle',
                    content : "Selecione uma forma de pagamento",
                });

                return false;
            }

            if(!cod_endereco){
                $.alert({
                    title : "Aviso",
                    theme: "modern",
                    type: "red",
                    icon: 'fas fa-exclamation-triangle',
                    content : "Selecione um endereço para entrega",
                });

                return false;
            }

            $.confirm({
                title: "Aviso",
                    content: "Deseja finalizar suas compras",
                    theme: "modern",
                    type: "orange",
                    icon: 'fas fa-question',
                    buttons:{
                        sim : {
                            text : "Sim",
                            btnClass: 'btn-warning', // class for the button
                            action : function(){
                                $.ajax({
                                    url:"src/usuarios/carrinho.php",
                                    type:"POST",
                                    data:{
                                        opc:'concluir',
                                        cod_venda,
                                        forma_pagamento,
                                        cod_endereco,
                                    },
                                    success:function(dados){
                                        console.log(dados);
                                        //$(".ms_corpo").append(dados);
                                        //Carregando('none');
                                        let retorno = JSON.parse(dados);

                                        if(retorno.status){
                                            $.alert({
                                                title : "Sucesso",
                                                theme: "modern",
                                                type: "green",
                                                icon: 'far fa-check-circle',
                                                content : retorno.msg
                                            })
                                        }else{
                                            $.alert({
                                                title : "Error",
                                                theme: "modern",
                                                type: "red",
                                                icon: 'fas fa-exclamation-triangle',
                                                content : retorno.msg
                                            })
                                        }

                                        AppComponentes('home');

                                        setTimeout(() => {
                                            PageClose();
                                        }, 1000);


                                    }
                                });
                            }
                        },
                        nao :{
                            text : "Não",
                            action : function(){

                            },

                        },
                    }
            });


        });

        $("img[atua<?=$md5?>]").off('click').on('click',function(){
            cod=$(this).attr("cod");
            console.log(cod);
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:'src/produtos/visualizar_produto.php?cod='+cod
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                    //Carregando('none');
                }
            });

        });


        $("img[incluir<?=$md5?>]").off('click').on('click',function(){

            local = $(this).parent("span");
            prod = local.attr("cod");
            $("img[incluir"+prod+"]").css("display","none");
            $("img[produto_mais"+prod+"]").css("display","inline");
            $("img[produto_menos"+prod+"]").css("display","inline");
            $("text[text"+prod+"]").css("display","inline");
            $("text[text"+prod+"]").text("1");
            $("h3[h3"+prod+"]").css("display","block");

            $(".compraOn").css("display","block");
            $(".compraOff").css("display","none");

            CarrinhoOpc(prod, 'novo');

        });

        $("img[produto_mais<?=$md5?>]").off('click').on('click',function(){
            local = $(this).parent("span");
            prod = local.attr("cod");
            qt = local.children("text").text();
            $("text[text"+prod+"]").text(qt*1+1);

            tot = (qt*1+1)*(local.attr("valor"));
            tot = tot.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
            $("h3[h3"+prod+"]").html(tot);
            $("h3[h3"+prod+"]").css("display","block");

            $(".compraOn").css("display","block");
            $(".compraOff").css("display","none");

            CarrinhoOpc(prod, 'mais');

        });

        $("img[produto_menos<?=$md5?>]").off('click').on('click',function(){
            local = $(this).parent("span");
            prod = local.attr("cod");
            qt = local.children("text").text();
            if(qt*1 == 1){

                $("img[incluir"+prod+"]").css("display","inline");
                $("img[produto_mais"+prod+"]").css("display","none");
                $("img[produto_menos"+prod+"]").css("display","none");
                $("text[text"+prod+"]").css("display","none");

                tot = (local.attr("valor")*1);
                tot = tot.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                $("h3[h3"+prod+"]").html(tot);
                $(".compraOff").css("display","block");
                $(".compraOn").css("display","none");

            }else{

                $("text[text"+prod+"]").text(qt*1-1);
                tot = (qt*1-1)*(local.attr("valor"));
                tot = tot.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                $("h3[h3"+prod+"]").html(tot);

            }

            CarrinhoOpc(prod, 'menos');
        });

        $(".endereco").off('click').on('click',function(){
            tela = $(this).attr("tela");
            Carregando();
            $.ajax({
                url:"componentes/ms_popup.php",
                type:"POST",
                data:{
                    local:'src/usuarios/endereco_entrega.php',
                    tela
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                    //Carregando('none');
                }
            });
        })

        $(".pagamento").off('click').on('click',function(){
            tela = $(this).attr("tela");
            Carregando();
            $.ajax({
                url:"componentes/ms_popup.php",
                type:"POST",
                data:{
                    local:'src/usuarios/formas_pagamento.php',
                    tela
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                    //Carregando('none');
                }
            });
        })



    })
</script>