<?php
    include("../lib/includes/includes.php");
?>
<style >
.ms_details_button_adcionar{
position: absolute;
width: 124px;
height: 40px;
left: 30px;
top: 747px;

}
.ms_button_menos{
position: absolute;
left: 0%;
right: 67.74%;
top: 0%;
bottom: 0%;

	}
.ms_button_mais{
position: absolute;
left: 67.74%;
right: 0%;
top: 0%;
bottom: 0%;
}
.qtd{
position: absolute;
left: 45.16%;
right: 45.16%;
top: 22.5%;
bottom: 17.5%;
font-family: Montserrat;
font-style: normal;
font-weight: 500;
font-size: 20px;
line-height: 24px;
text-align: center;
color: #777777;
}
</style>

<div class=" ms_details_button_adcionar">	
<span>

<img class="ms_button_menos" src="svg/button_menos.svg ">

<div class="qtd"><?=$i+1?></div>

<img class="ms_button_mais" src="svg/mais.svg ">

</span>
</div>

<script >
	
        $(".ms_button_mais").click(function(){
            local = $(".qtd")
            qt = Number(local.text());
            local.text(qt*1+1);
        });

	   $(".ms_button_menos").click(function(){
            local = $(this).parent("span");
            qt = local.children(".qtd").text();
            if(qt*1 == 1){
                local.children(".ms_button_mais");
                local.children(".ms_button_menos");
                local.children(".qtd");
            }else{
                local.children(".qtd").text(qt*1-1);
            }

        });
</script>