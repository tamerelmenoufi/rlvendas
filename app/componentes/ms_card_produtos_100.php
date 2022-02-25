<?php

    include("../../../lib/includes.php");

    ProdutosCarrinho();

    $query = "SELECT * FROM `produtos`";
    $result = mysql_query($query);


?>
<style>
    .ms_card_produtos_100{
        padding:5px;
    }
    .ms_card_produtos_100 div{
        position:relative;
        height:120px;
        background-color: #EBF4F1;
        border-radius:10px;
        border-bottom-right-radius:25px;
        float:none;
        text-align:left;
    }
    .ms_card_produtos_100 h4{
        color:#194B38;
        font-size:18px;
/*           width:251px;*/
    height:auto;
    font-style: normal;
    line-height: 14px;
    margin-top:5px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    padding-bottom:4px;
    }
    .ms_card_produtos_100 h3{
        color:#4CBB5E;
        font-size:24px;
    }
    .ms_card_produtos_100 p{
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
    .ms_card_produtos_100 font{
        position:relative;
        color:#9C9C9C;
        font-size:8px;
        font-weight:bold;
    }
    .ms_card_produtos_100 span{
        position:absolute;
        right:0px;
        bottom:0;
    }
    .ms_card_produtos_100 text{
        padding-left:10px;
        padding-right:10px;
        color:#9C9C9C;
        font-weight:bold;
        font-size:12px;
    }
</style>


<div class="w3-row">

    <?php

    while ($d =  mysql_fetch_object($result) ) {

    ?>
    <div class="w3-col s12 ms_card_produtos_100">
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
    ?>
</div>

<script>
    $(function(){

        Carregando('none');

        /*
        $("img[atua]").each(function(){
            cod = $(this).attr("cod");
            obj = $(this);
            $.ajax({
                url:"lib/includes/img.php?cod="+cod,
                success:function(dados){
                    obj.attr("src",dados);
                    //console.log(dados);
                }
            });
        });
        //*/

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