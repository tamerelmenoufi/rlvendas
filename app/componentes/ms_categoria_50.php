<?php
    include("../../../../lib/includes.php");

// $cod_produto = $_POST['codigo'];
//     $query = "SELECT * FROM `produtos` where codigo = '{$cod_produto}'";
//     $result = mysql_query($query);
//     $d =  mysql_fetch_object($result);

?>
<style>
    .ms_visualizar_categoria{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        height:100%;
        padding:0px;
    }
    .ms_visualizar_categoria div{
        position:absolute;
        width:100%;
        height:100%;
        background-color: #FFFFFF;
        text-align:left;
    }
    .ms_visualizar_categoria h2{
        color:#194B38;
        font-size:30px;
        margin-bottom:20px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .ms_visualizar_categoria h3{
        position:absolute;
        bottom:20px;
        left:0;
        padding:10px;
        color:#4CBB5E;
        font-size:30px;
    }
    .ms_visualizar_categoria h5{
        position:absolute;
        bottom:0;
        left:10px;
        padding:5px;
        color:#777777;
        font-size:10px;
    }
    .ms_visualizar_categoria p{
        color:#777777;
        font-size:18px;
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
    .ms_visualizar_categoria font{
        position:relative;
        color:#9C9C9C;
        font-size:8px;
        font-weight:bold;
    }
    .ms_visualizar_categoria span{
        position:absolute;
        right:0px;
        bottom:0;
    }
    .ms_visualizar_categoria text{
        padding-left:10px;
        padding-right:10px;
        color:#9C9C9C;
        font-weight:bold;
        font-size:18px;
    }
</style>


<div>

<div>edfbjadhf</div>
</div>
<script>
    $(function(){

        //.off('click').on('click',
        // Carregando('none');
        // $("campo_obs").off('click').on('click', function(){
        //     Carregando();
        //     $.ajax({
        //         url:"componentes/ms_popup_obs.php",
        //         success:function(dados){
        //             $(".ms_corpo").append("<div ms_popup_obs>"+dados+"</div>");
        //             Carregando('none');
        //         },
        //         error:function(){
        //             $.alert("Ocorreu um erro no carregamento da página!");
        //             Carregando('none');
        //         }
        //     });
        // })


        $("img[incluir]").off('click').on('click',function(){
            local = $(this).parent("span");
            local.children("img[incluir]").css("display","none");
            local.children("img[produto_mais]").css("display","inline");
            local.children("img[produto_menos]").css("display","inline");
            local.children("text").css("display","inline");
            local.children("text").text("1");
            local.parent("div").children("h3").css("display","block");

            $(".compraOn").css("display","block");
            $(".compraOff").css("display","none");

        });

        $("img[produto_mais]").off('click').on('click',function(){
            local = $(this).parent("span");
            qt = local.children("text").text();
            local.children("text").text(qt*1+1);

            tot = (qt*1+1)*(local.attr("valor"));
            tot = tot.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
            local.parent("div").children("h3").html(tot);
            local.parent("div").children("h3").css("display","block");

            $(".compraOn").css("display","block");
            $(".compraOff").css("display","none");

        });

        $("img[produto_menos]").off('click').on('click',function(){
            local = $(this).parent("span");
            qt = local.children("text").text();
            if(qt*1 == 1){
                local.children("img[incluir]").css("display","inline");
                local.children("img[produto_mais]").css("display","none");
                local.children("img[produto_menos]").css("display","none");
                local.children("text").css("display","none");

                tot = (local.attr("valor")*1);
                tot = tot.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                local.parent("div").children("h3").html(tot);
                local.parent("div").children("h3").css("display","none");

                $(".compraOff").css("display","block");
                $(".compraOn").css("display","none");

            }else{
                local.children("text").text(qt*1-1);

                tot = (qt*1-1)*(local.attr("valor"));
                tot = tot.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                local.parent("div").children("h3").html(tot);

            }
        });
    })
</script>