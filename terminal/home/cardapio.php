<?php
    include("../../lib/includes.php");
?>
<style>
    .cardapio{
        position:absolute;
        left:0;
        top:0;
        bottom:0;
        width:100%;
        overflow:auto;
    }
    .itens<?=$md5?>{
        margin:10px;
    }
    .item_grup<?=$md5?>{
        margin-top:40px;
    }

    .item_button<?=$md5?>{
        height:100px;
        text-align:center;
    }

    .item_icone<?=$md5?>{
        font-size:40px;
    }

    div[foto<?=$md5?>]{
        width:25%;
        height:100%;
        background-size:cover;
        background-position:center;
        float:left;
        border-top-left-radius:5px;
        border-bottom-left-radius:5px;

    }
    div[texto<?=$md5?>]{
        width:75%;
        height:100%;
        text-align:center;
        padding-top:30px;
        float:right;
        font-size:20px;
        font-weight:bold;
    }

</style>
<div class="cardapio">
    <h3 style="text-align:center; padding:20px;">
        <i class="fa-brands fa-elementor"></i>
        CARDÁPIO
    </h3>
    <div class="row itens<?=$md5?>">
<?php
    $query = "select * from categorias where deletado != '1' and situacao = '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
        <div class="col-md-6 item_grup<?=$md5?>">
            <button
                    type="button"
                    class="btn btn-warning btn-block item_button<?=$md5?>"
                    categoria="<?=$d->codigo?>"
                    style="padding:0px; display: table;"
            >
                <div foto<?=$md5?> style="background-image:url(../painel/categorias/icon/<?=$d->icon?>)"></div>
                <div texto<?=$md5?>><?=$d->categoria?></div>
                <!-- <i class="fa-solid fa-martini-glass-citrus item_icone<?=$md5?>"></i> -->
            </button>
        </div>
<?php
    }
?>
    </div>
</div>



<div style="position:fixed; right:20px; bottom:20px; display:none">
    <button class="btn btn-danger btn-lg btn-block" sair_venda>SAIR DO PEDIDO <?=$_SESSION['ConfMesa']?></button>
</div>

<script>
    $(function(){

        <?php
        if(!$_SESSION['ConfMesa']){
        ?>
        window.localStorage.clear();
        <?php
        }
        ?>

        ConfMesa = window.localStorage.getItem('ConfMesa');

        if(ConfMesa){
            $("button[sair_venda]").parent("div").css("display","block");
        }

        $(".item_button<?=$md5?>").click(function(){

            ConfMesa = window.localStorage.getItem('ConfMesa');

            if(!ConfMesa){
                JanelaDefineMesa = $.dialog({
                    content:"url:home/definir_mesa.php",
                    title:false,
                    columnClass:"col-md-8 col-md-offset-2",
                    closeIcon: false,
                });
                return false;
            }



            title = $(this).children("p").html();
            categoria = $(this).attr("categoria");

            $.ajax({
                url:"cardapio/produtos.php",
                data:{
                    categoria
                },
                success:function(dados){
                    $("#body").html(dados);
                }
            });
        });

        $("button[sair_venda]").click(function(){
            $.confirm({
                content:"<center>Deseja realmente sair do seu pedido em <b>PEDIDO <?=$_SESSION['ConfMesa']?></b>?</center>",
                title:false,
                buttons:{
                    'SIM':function(){
                        window.localStorage.clear();
                        $.ajax({
                            url:"home/index.php?sair=1",
                            success:function(dados){
                                $("#body").html(dados);
                            }
                        });
                    },
                    'NÃO':function(){

                    }
                }
            });
        });

    })
</script>
