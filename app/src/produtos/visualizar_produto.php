<?php
    include("../../../../lib/includes.php");

    ProdutosCarrinho();

    if($_GET['cod']) $cod = $_GET['cod'];
    if($_POST['cod']) $cod = $_POST['cod'];

    $query = "SELECT * FROM `produtos` where codigo = '{$cod}'";
    $result = mysql_query($query);
    $d =  mysql_fetch_object($result);

?>
<style>
    .ms_visualizar_produto{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        height:100%;
        padding:0px;
    }
    .ms_visualizar_produto div{
        position:absolute;
        width:100%;
        height:100%;
        background-color: #FFFFFF;
        text-align:left;
    }
    .ms_visualizar_produto h2{
        color:#194B38;
        font-size:30px;
        margin-bottom:10px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    .ms_visualizar_produto h3{
        position:absolute;
        bottom:20px;
        left:0;
        padding:10px;
        color:#4CBB5E;
        font-size:30px;
    }
    .ms_visualizar_produto h5{
        position:absolute;
        bottom:0;
        left:10px;
        padding:5px;
        color:#777777;
        font-size:10px;
    }
    .ms_visualizar_produto p{
        color:#717171;
        font-size:16px;
        text-align:justify;
        width:100%;
        height:auto;
        font-style: normal;
        margin-top:5px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;

    }
    .ms_visualizar_produto font{
        position:relative;
        color:#9C9C9C;
        font-size:8px;
        font-weight:bold;
    }
    .ms_visualizar_produto span{
        position:absolute;
        right:0px;
        bottom:0;
    }
    .ms_visualizar_produto text{
        padding-left:10px;
        padding-right:10px;
        color:#9C9C9C;
        font-weight:bold;
        font-size:18px;
    }
</style>


<div
    style="
            position:fixed;
            left:-100%;
            top:-10%;
            width:300%;
            height:65%;
            background-color:#EBF4F1;
            z-index:101;
            border-radius:100%;
            background-image:url(<?=$config['url_produtos'].$d->codigo.'/300.png'?>);
            background-size:cover;
            background-position:center center;
            opacity:0.3;
            filter: blur(10px);
            text-align:center;
            ">
</div>

<img    src="<?=$config['url_produtos'].$d->codigo.'/300.png'?>"
        style="
                position:fixed;
                top:50px;
                left:16%;
                z-index:102;
                width:70%;
                " />

<i
    class="
            fas
            fa-shopping-bag
            fa-2x
            compraOff
            "
    style="
            position:fixed;
            top:15px;
            right:20px;
            color:#777777
            "
></i>

<i
    class="
            fas
            fa-shopping-bag
            fa-2x
            compraOn
            animate__animated
            animate__tada
            "
    style="
            position:fixed;
            top:15px;
            right:20px;
            color:green;
            "
></i>


<div style="position:fixed; top:55%; bottom:0; left:0; right:0; z-index:103;">

    <div class="w3-row">
        <div class="w3-col s12 ms_visualizar_produto">
            <div cod="<?=$d->codigo?>" class="w3-padding">
                <h2><?=utf8_encode($d->prd_produto)?></h2>
                <p><?= utf8_encode($d->prd_descricao)?></p>

                <h3 h3<?=$d->codigo?>>R$ <?= number_format($d->prd_valor*(($Carrinho['produto'][$d->codigo])?$Carrinho['produto'][$d->codigo]:'1'), 2, ',',',');?><font>/Kg</font></h3>
                <span valor='<?= $d->prd_valor?>' cod="<?=$d->codigo?>">
                    <img incluir<?=$md5?> incluir<?=$d->codigo?> src="svg/botao_mais_right.svg" style="display:<?=(($Carrinho['produto'][$d->codigo])?'none':'inline')?>; margin-bottom:5px; margin-right:5px; height:60px;" />
                    <img produto_menos<?=$md5?> produto_menos<?=$d->codigo?> src="svg/btn_menos.svg" style="margin-bottom:5px; display:<?=(($Carrinho['produto'][$d->codigo])?'inline':'none')?>; margin-bottom:5px; height:60px;" />
                    <text text<?=$d->codigo?> style="display:<?=(($Carrinho['produto'][$d->codigo])?'inline':'none')?>;"><?=(($Carrinho['produto'][$d->codigo])?$Carrinho['produto'][$d->codigo]:'1')?></text>
                    <img produto_mais<?=$md5?> produto_mais<?=$d->codigo?> src="svg/btn_mais.svg" style="margin-bottom:5px; display:<?=(($Carrinho['produto'][$d->codigo])?'inline':'none')?>; margin-bottom:5px; height:60px;" />
                </span>

                <campo style="position: absolute; left:10px; right:10px;">
                    Observações
                    <campo_obs cod="<?=$Carrinho['codigo'][$d->codigo]?>" codigo="<?=$d->codigo?>" class="form-control" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                        <?=(($Carrinho['obs'][$d->codigo])?:'Digite aqui suas observações')?>
                    </campo_obs>
                </campo>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        //.off('click').on('click',
        Carregando('none');

        $(".ms_visualizar_produto h2, .ms_visualizar_produto p").off('click').on('click', function(){
            cod = $(this).parent("div").attr("cod");
            Carregando();
            $.ajax({
                url:"componentes/ms_popup.php",
                type:"POST",
                data:{
                    local:"src/produtos/visualizar_produto_descritivo.php?cod="+cod,
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                },
                error:function(){
                    $.alert("Ocorreu um erro no carregamento da página!");
                    Carregando('none');
                }
            });
        })



        $("campo_obs").off('click').on('click', function(){
            cod = $(this).attr("cod");
            codigo = $(this).attr("codigo");
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_obs.php?cod="+cod+"&codigo="+codigo,
                success:function(dados){
                    $(".ms_corpo").append("<div ms_popup_obs>"+dados+"</div>");
                    Carregando('none');
                },
                error:function(){
                    $.alert("Ocorreu um erro no carregamento da página!");
                    Carregando('none');
                }
            });
        })


        $("img[incluir<?=$md5?>]").off('click').on('click',function(){

            if(ms_cli_codigo){
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
            }else{
                AlertaLogin();
            }

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
    })
</script>