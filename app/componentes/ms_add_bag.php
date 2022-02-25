<?php
    include("../lib/includes/includes.php");
?>
<style >

.ms_add{
top: 730px;
display: flex;
flex-direction: row;
align-items: flex-end;
padding: 0px;
position: absolute;
right: 0px;
	}

</style>

<div >
<div class="ms_add_bag">	

<img class="ms_add"local="src/info/index_add_bag.php" componente="ms_popup" src="svg/add_to_bag.svg ">

</div>

</div>

<script>
    $(function(){
        $(".ms_add").click(function(){
            local = $(this).attr('local');
            componente = $(this).attr('componente');
            $.ajax({
                url:"componentes/"+componente+".php",
                type:"POST",
                data:{
                    local
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });
        });
    })
</script>

