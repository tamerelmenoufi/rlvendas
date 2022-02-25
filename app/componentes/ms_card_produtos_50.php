<?php
   include("../../../lib/includes.php");

   ProdutosCarrinho();

    $Busca = explode(" ",utf8_decode($_POST['busca']));
    if($Busca){
        $TBusca = [];
        foreach($Busca as $ind => $val){
            if(trim($val) and strlen(trim($val)) > 2 ){
                $TBusca[] = "prd_produto like '%{$val}%' or  prd_descricao like '%{$val}%'";
            }
        }
        if($TBusca){
            $Busca = " and (".implode(" or ", $TBusca).")";
        }else{
            exit();
        }
    }

    $query="SELECT * FROM `produtos` where 1 {$Busca} limit 50";
    $result = mysql_query($query);

?>
<style>
    .ms_card_produtos_sub_categoria_50{
        padding:5px;
    }
    .ms_card_produtos_sub_categoria_50 div{
        position:relative;
        height:240px;
        background-color: #EBF4F1;
        border-radius:15px;
        float:none;
        text-align:left;
    }
    .ms_card_produtos_sub_categoria_50 h4{
        color:#194B38;
        font-size:18px;
        position: relative;
        top: 10px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    .ms_card_produtos_sub_categoria_50 h3{
        color:#4CBB5E;
        font-size:22px;
        margin-top:5px;

    }
    .ms_card_produtos_sub_categoria_50 p{
        color:#777777;
        font-size:12px;
        text-align:justify;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        margin:0;
        padding:0;
    }
    .ms_card_produtos_sub_categoria_50 font{
        position:relative;
        color:#9C9C9C;
        font-size:8px;
        font-weight:bold;
    }
    .ms_card_produtos_sub_categoria_50 span{
        position:absolute;
        right:0;
        bottom:0;
    }
    .ms_card_produtos_sub_categoria_50 text{
        padding-left:10px;
        padding-right:10px;
        color:#9C9C9C;
        font-weight:bold;
        font-size:12px;
    }
    .ms_card_produtos_sub_categoria_50_topo{
        position:fixed;
        left:0;
        top:0;
        width:100%;
        height:65px;
        background:#fff;
        text-align:center;
        color:#777;
        font-size:18px;
        font-weight:bold;
        z-index:10;
        padding:15px;
        display:<?=(($_POST['categoria_descricao'])?'block':'none')?>;
    }
    .ativa_carrinho{
        position:fixed;
        right:15px;
        top:15px;
        z-index:11;
        color:#eee;
    }
</style>

<div class="ms_card_produtos_sub_categoria_50_topo"><?=$_POST['categoria_descricao']?></div>
<div class="ativa_carrinho">
    <i class="fas fa-cart-arrow-down fa-2x"></i>
</div>


<div class="w3-row">
    <p class="w3-padding"><b><?=$_POST['sub_categoria_descricao']?></b></p>
  <?php
    while ($d = mysql_fetch_object($result)){
  ?>
    <div opc="<?=$i?>" class="w3-col s6 ms_card_produtos_sub_categoria_50">
        <div class="w3-padding">
            <center>
            <img
                atua<?=$md5?>
                cod="<?=$d->codigo?>"
                src="<?=$config['url_produtos'].$d->codigo.'/100.png'?>"
                style="margin-top:0px; width:100px;height:100px; margin-right:10px;"
            />
            </center>
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
        $("img[atua<?=$md5?>]").off('click').on('click',function(){
            opc = $(this).attr("opc");
            cod = $(this).attr("cod");
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:'src/produtos/visualizar_produto.php',
                    cod
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

        $(".ativa_carrinho").off('click').on('click',function(){

            $.ajax({
                    url:"componentes/ms_popup_100.php",
                    type:"POST",
                    data:{
                        local:"src/usuarios/carrinho.php",
                    },
                    success:function(dados){
                        //$(".ms_corpo").append("<div barra_busca_topo>"+dados+"</div>");
                        $(".ms_corpo").append(dados);
                    }
                });

        })

    })
</script>