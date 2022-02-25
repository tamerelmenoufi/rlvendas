<?php
    include("../../../../lib/includes.php");
?>
<style>

</style>

<i
    class="
            fas
            fa-shopping-bag
            fa-2x
            compraOff
            "
    style="
            position:fixed;
            top:15px;
            right:20px;
            color:#777777
            "
></i>

<i
    class="
            fas
            fa-shopping-bag
            fa-2x
            compraOn
            animate__animated
            animate__tada
            "
    style="
            position:fixed;
            top:15px;
            right:20px;
            color:green;
            "
></i>

    <object categorias componente="ms_sub_categoria_scroll" get="<?=$get?>" post="<?=$post?>"></object>
    <object categorias componente="ms_card_produtos_sub_categoria_50" ></object>

<script>
    $(function(){
        AppComponentes('categorias');
    })
</script>