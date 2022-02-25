<?php
    include("../lib/includes/includes.php");
?>
<style>


</style>


<div  class="w3-card">
    <div>
       <p>w3-card</p>
    </div>
</div>

<script>
    $(function(){
        $(".ms_card_promocoes_scroll_card").click(function(){
            opc = $(this).attr("opc");
            $.alert(opc);
        });

    })
</script>