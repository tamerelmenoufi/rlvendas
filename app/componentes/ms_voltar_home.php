<?php
    include("../lib/includes/includes.php");
?>
<style >

.ms_voltar_home{
position:fixed;
right:0px;
top:20px;
height:60px;

}
.button_voltar{
position: fixed;
height: 50px;
width: 50px;
left: 40px;
top: 20px;
	}


</style>

<div>
<div class=" ms_voltar_home">	

<img class="button_voltar" src="svg/Back.svg ">

</div>

</div>

<script>
$(function(){

        //new WOW().init();

        $(".button_voltar").click(function(){
            $.ajax({
            url:"./src/home/home.php",
            success:function(dados){
             $(".ms_corpo").html(dados);
            },
            error:function(){
             $.alert("Ocorreu um erro no carregamento da página!");
            }
        });
});


    })
</script>