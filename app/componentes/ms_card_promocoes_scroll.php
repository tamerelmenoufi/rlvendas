<?php

    include("../../../lib/includes.php");
    $query = "SELECT * FROM `promocao` where prom_situacao='1'";
    $result = mysql_query($query);
?>
<style>
.ms_card_promocoes_scroll_palco {
    overflow-x: auto;
}
.ms_card_promocoes_scroll {
   display: flex;
   flex-direction: row;
   justify-content: left;
   align-items: left;
   width: 100%;
   padding:0px;
   overflow:scroll;
}
.ms_card_promocoes_scroll_card {
   min-width: 350px;
   height: 180px;
   text-align: center;
   border:0;
   background:transparent;
   margin:5px;
}
.ms_card_promocoes_scroll_card div{
    position:relative;
    width:350px;
    height:180px;
    background:transparent;
    border-radius:10px;
    float:none;
    text-align:center;
    background-size:cover;
}

.ms_card_promocoes_scroll::-webkit-scrollbar {
    display: none;
}

</style>


<div class="ms_card_promocoes_scroll_palco">
  <div class="ms_card_promocoes_scroll">
  <?php

    while ($d =  mysql_fetch_object($result) ) {

    ?>
    <div  class="ms_card_promocoes_scroll_card">

    <img style="height:180px;width:350px;" src="<?=$config['url_promocao'].$d->codigo.'.png'?>" />
        </div>

  <?php
    }
  ?>
  </div>
</div>

<script>
    $(function(){
        Carregando('none');
        $(".ms_card_promocoes_scroll_cardXXX").off('click').on('click',function(){
          opc = $(this).attr("opc");
          produto = $(this).attr("produto");
          Carregando();
          $.ajax({
              url:"componentes/ms_popup_100.php",
              type:"POST",
              data:{
                  local:'src/produtos/visualizar_produto.php?cod=xxx',
                  produto
              },
              success:function(dados){
                  $(".ms_corpo").append(dados);
                  //Carregando('none');
              }
          });
        });

    })
</script>
