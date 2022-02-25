<?php
      include("../../../lib/includes.php");

if($_SESSION['ms_cli_codigo']){

    $select = "select * from clientes_enderecos where cli_codigo = '{$_SESSION['ms_cli_codigo']}' and cli_end_padrao = '1'";
    $result = mysql_query($select);
    $d = mysql_fetch_object($result);
}
?>
<style>
    .ms_barra_topo_fixo{
        position:fixed;
        left:0px;
        top:0;
        width:100%;
        height:60px;
        background:#fff;
        z-index: 5;
    }
    .ms_barra_topo_fixo div{
        width:70%;
        height:40px;
        margin-top:10px;
        margin-left:10px;
        border:solid 1px #9C9C9C;
        border-radius:10px;
        background-image:url("svg/icone_select_down.svg");
        background-position:bottom 12px right 10px;
        background-repeat:no-repeat;
        text-align:center;
        padding:10px;
    }
    .ms_barra_topo_fixo span{
        position: absolute;
        width:40px;
        height:40px;
        right:10px;
        top:10px;
        border:solid 1px #9C9C9C;
        border-radius:10px;
        padding:10px;
        text-align:center;
    }
    .ruaTexto{
        width: 191px;
        height: auto;
        line-height: 17px;
        text-align: center;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        margin-bottom: -2px;
    }
    .ativa_carrinho{
        position:fixed;
        right:15px;
        top:15px;
        z-index:11;
        color:#eee;
    }

</style>

<div style="height:70px;"></div>
<div class="ms_barra_topo_fixo">
    <div
        class="topo_acao"
        >
        <i class="fas fa-map-marker-alt" ></i>
        <label class="ruaTexto"><?=((utf8_encode($d->cli_end_rua))?:'<font style="color:red">Endereço não definido</font>')?></label>

    </div>
    <!--
    <span
        class="topo_acao_info"
        local="src/info/index.php"
        componente="ms_popup"
    >
        <i class="fas fa-bell"></i>
    </span>
    -->
</div>

<div class="ativa_carrinho">
    <i class="fas fa-cart-arrow-down fa-2x"></i>
</div>

<script>
    $(function(){
        Carregando('none');
        $(".topo_acao").off('click').on('click',function(){
              if(ms_cli_codigo > 0){
            local = "src/usuarios/lista_end.php";
               componente ="ms_popup_100";
            }else{
            local = "src/usuarios/index.php";
               componente = "ms_popup";
            }
            // local = $(this).attr('local');
            // componente = $(this).attr('componente');

            Carregando();
            $.ajax({
                url:"componentes/"+componente+".php",
                type:"POST",
                data:{
                    local
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                    //Carregando('none');
                }
            });
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