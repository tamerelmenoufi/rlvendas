<?php
    include("../../lib/includes/includes.php");
?>

<style>
.prd-corpo{
	background-color: white;
}
</style>

<body>
    <div class="container">
        <div class="row"> 
            <div class="col-md-12"> 
                <div class="prd-corpo" >
                    <object produto componente ="ms_prd"></object>
                    <object produto componente="ms_voltar_home"></object>   
                    <object produto componente="ms_sacola"></object>
                    <object produto componente="ms_free_shipping"></object>
                    <object produto componente="ms_add_bag"></object>
                    <object produto componente="ms_pontuacao"></object>
                    <object produto componente="ms_produto_details_button"></object>
                    <object produto componente="ms_details_preco"></object>
                    <object produto componente="ms_details_button_adcionar"></object>
                    <!-- <object produto componente="ms_barra_topo_fixo" ></object> -->
                </div>
            </div>
        </div>
    </div>
</body>


<script>
    $(function(){
        AppComponentes('produto');
    })
</script>