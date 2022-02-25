<?php
   include("../../../../lib/includes.php");


    $query_end="SELECT * FROM clientes_enderecos WHERE cli_codigo ='{$_SESSION['ms_cli_codigo']}'";
    $result = mysql_query($query_end);
    //$d_end = mysql_fetch_object($result);

?>
<style>
.ms_usuario_endereco_titulo_topo{
    position:fixed;
    left:0;
    top:0;
    width:100%;
    height:65px;
    background-color:rgba(255,255,255,0.6);;
    text-align:center;
    color:#777;
    font-size:18px;
    font-weight:bold;
    z-index:10;
    padding:15px;
}

.ms_usuario_endereco_mapa{
    position:fixed;
    top:-20px;
    left:-100%;
    bottom:60%;
    width:300%;
    border-radius:90%;
    z-index:9;
}

.ms_usuario_endereco_titulo{
    position:relative;
    margin-top:55%;
    width:100%;
    text-align:left;
    color:#194B38;
    font-size:22px;
    font-weight:bold;
    padding:15px;
}

.ms_usuario_endereco_titulo_form{
    position:relative;
    width:100%;
    border:solid 0px red;
    margin-bottom:15px;
}
.ms_usuario_endereco_titulo_form_rotulo{
    position:relative;
    margin-bottom:0px;
    margin-left:15px;
    color:#ccc;
    font-size:12px;
}

.ms_usuario_endereco_titulo_form_campo{
    position:relative;
    width:100%;
    padding:0px 10px 10px 10px;
    height:50px;
}

.ms_usuario_endereco_titulo_form_campo input{
    position:relative;
    width:100%;
    height:50px;
    background-color:#F1F3F2;
    background-position:left 15px center;
    background-size:20px;
    background-repeat:no-repeat;
    color:#777;
    border-radius:10px;
    padding-left:45px;
    padding-right:5px;
    font-size:18px;
    border:0;
}
    .ms_usuario_endereco_titulo_form_campo select{
    position:relative;
    width:100%;
    height:50px;
    background-color:#F1F3F2;
    background-position:left 15px center;
    background-size:20px;
    background-repeat:no-repeat;
    color:#777;
    border-radius:10px;
    padding-left:45px;
    padding-right:5px;
    font-size:18px;
    border:0;
}

.ms_usuario_endereco_titulo_form_campo svg{
    position:absolute;
    left:20px;
    top:15px;
    color:#777;
    font-size:20px;
    z-index:1;
    }
.ms_usuario_compras_100_click{
    position:relative;
    width:100%;
    height:120px;
    margin-bottom:10px;
}

 .ms_usuario_compras_100{
    position:relative;
    width:100%;
    height:65px;
    margin-bottom:10px;
}
    .ms_usuario_compras_100_item{
    position:absolute;
    left:10px;
    right:10px;
    height:100%;
    background-color:#F1F3F2;
    padding-top:10px;
    padding-left:20px;
    padding-right:10px;
    border-radius:25px;
    color:#777777;
    cursor:pointer;
    }

    .ms_usuario_compras_100_item_click{
    border: solid 2px #4CBB5E;
    background-color: #F1F3F2;
    border-radius: 20px;
}

    .end_principal{
    position: relative;
    padding-left: 15px;
    padding-bottom: 15px;
}
.ms_usuario_end_novo_end{
      position:relative;
        width:100%;
        height: 101px;

}
.ms_usuario_end_novo_end_item{
position: absolute;
    left: 10px;
    right: 9px;
    height: 43%;
    background-color: #4CBB5E;
    padding-top: 10px;
    /* padding-left: 78px; */
    /* padding-right: 10px; */
    border-radius: 25px;
    color: #777777;
    cursor: pointer;
    text-align: center;
}
.ms_usuario_add_editar{
    position: absolute;
    right: 0;
    z-index: 1;
    bottom: 60%;
    width: 47px;
    height: 47px;
    padding: 20px;
    margin-left: 292px;
    margin-top: 20px;
    /* background-color: red;*/
}
.cli_end_apelido{
width: 191px;
    /* height: auto; */
    line-height: 28px;
    /* text-align: center; */
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    margin-bottom: -2px;
    align-items: flex-start;
    align-self: initial;
    color: #194B38;
}
.cli_end_rua{
    width: 250px;
    height: auto;
    /* line-height: 14px; */
    /*text-align: center;*/
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    /* margin-bottom: -2px; */
    margin-top: 0px;
}
.cli_end_ref{
    width: 227px;
    height: auto;
    line-height: 17px;
    /*text-align: center;*/
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    /* margin-bottom: -2px; */
    margin-top: -8px;
    font-size: 12px;
}
.botao_novo_endereco{
    position:relative;
    width:100%;
    text-align:center;
    margin-bottom:20px;
}

</style>


<div tela_lista class="container" style="padding-top:0px;">
    <div class="botao_novo_endereco">
        <button class="btn btn-success">Adicionar um endereço</button>
    </div>
<?php
while($d = mysql_fetch_object($result)){
?>
<div>
<div
    card<?=$d->codigo?>
    class="ms_usuario_compras_100 <?=(($d->cli_end_padrao == '1')?'ms_usuario_compras_100_item_click':false)?>"

    dots
    acao<?=$md5?>
    local="src/usuarios/lista_end_opc.php"
    descricao="<?=utf8_encode($d->cli_end_rua)?>"
    codigo="<?=$d->codigo?>"


>

    <div class="ms_usuario_compras_100_item">

        <p card_rua class="cli_end_rua">
            <b><?=utf8_encode($d->cli_end_apelido)?></b><br>
            <?=utf8_encode($d->cli_end_rua)?>
        </p>

    </div>

</div>
</div>
<?php
}
?>


</div>
<script>

    $(function(){
        Carregando('none');

        $(".botao_novo_endereco").off('click').on('click',function(){
            PageClose();

            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:"src/usuarios/cadastro/endereco.php",
                    ativar:'1',
                },
                success:function(dados){
                    //$(".ms_corpo").append("<div barra_busca_topo>"+dados+"</div>");
                    $(".ms_corpo").append(dados);
                    AppComponentes('home');
                }
            });

        });

        $(".ms_usuario_compras_100_item").off('click').on('click',function(){
            local = $(this).children("p[card_rua]").html();
            cod = $(this).attr("cod");
            $(".endereco<?=$_POST['md5']?>").children("p[local_entrega]").html(local);
            $(".endereco<?=$_POST['md5']?>").attr("cod", cod);

            PageClose();
        })

    })
</script>