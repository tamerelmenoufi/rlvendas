<?php
    include("../../../lib/includes.php");

    $query="SELECT a.*, b.cat_categoria FROM sub_categoria_produto a left join categoria_produto b on a.subcat_categoria = b.codigo where a.subcat_categoria ='{$_POST['categoria']}'";
    $result = mysql_query($query);
?>
<style>
.ms_sub_categoria_scroll_palco {
    overflow-x: auto;
}
.ms_sub_categoria_scroll {
   display: flex;
   flex-direction: row;
   justify-content: left;
   align-items: left;
   width: 100%;
   padding:0px;
   overflow:scroll;
}
.ms_sub_categoria_scroll_card {
   min-width: 80px;
   text-align: center;
   border:0;
   background:transparent;
   margin:5px;
}
.ms_sub_categoria_scroll_card div{
    position:relative;
    margin-top: 5px;
    width:80px;
    height:80px;
    background-color: #EBF4F1;
    border-radius: 23px;
    float:none;
    text-align:center;
}
.ms_sub_categoria_scroll_card p{
    position:relative;
    width:80px;
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
.ms_sub_categoria_scroll::-webkit-scrollbar {
    display: none;
}

</style>


<div class="ms_sub_categoria_scroll_palco">

  <div class="ms_sub_categoria_scroll">


    <div

        categoria="<?=$_POST['categoria']?>"
        categoria_descricao="<?=$_POST['categoria_descricao']?>"
        class="ms_sub_categoria_scroll_card">
        <div style="background-color:#13687594">
            <img
                    class="img-circle"
                    src="<?=$_POST['icone']?>"
                    style="
                        margin-top:7px;
                        width: 65px;
                        height: 66px;
                        border-radius:20px;
                    " />
        </div>
        <p><?=$_POST['categoria_descricao']?></p>
    </div>


     <?php

    while ($d = mysql_fetch_object($result) ) {
    ?>
    <div
        opc="<?=$d->codigo?>"
        categoria=""
        sub_categoria="<?=$d->codigo?>"
        categoria_descricao="<?=utf8_encode($d->cat_categoria)?>"
        sub_categoria_descricao="<?=utf8_encode($d->subcat_descricao)?>"
        class="ms_sub_categoria_scroll_card">
        <div>
            <img
                    class="img-circle"
                    src="<?=$config['url_subcategorias'].'subcategorias/'.$d->codigo.'.png'?>"
                    style="
                        margin-top:7px;
                        width: 65px;
                        height: 66px;
                        border-radius:20px;
                    " />
        </div>
        <p><?=utf8_encode($d->subcat_descricao)?></p>
    </div>
  <?php
    }
  ?>
  </div>
</div>

<script>
    $(function(){
        Carregando('none');
        $(".ms_sub_categoria_scroll_card").off('click').on('click',function(){
            opc = $(this).attr("opc");
            categoria = $(this).attr("categoria");
            sub_categoria = $(this).attr("sub_categoria");
            categoria_descricao = $(this).attr("categoria_descricao");
            sub_categoria_descricao = $(this).attr("sub_categoria_descricao");

            Carregando();
            $.ajax({
                url:"componentes/ms_card_produtos_sub_categoria_50.php",
                type:"POST",
                data:{
                    categoria,
                    sub_categoria,
                    categoria_descricao,
                    sub_categoria_descricao,
                },
                success:function(dados){
                    $('object[componente="ms_card_produtos_sub_categoria_50"').html(dados);
                }
            });
        });

    })
</script>