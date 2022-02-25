<?php
    include("../../../../lib/includes.php");
    if($_GET['cod']) $cod = $_GET['cod'];
    if($_POST['cod']) $cod = $_POST['cod'];

    $query = "SELECT * FROM `produtos` where codigo = '{$cod}'";
    $result = mysql_query($query);
    $d =  mysql_fetch_object($result);

?>
<style>
    .ms_visualizar_produto_descritivo{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        height:100%;
        padding:0px;
        padding-top:10px;
    }
    .ms_visualizar_produto_descritivo div{
        position:absolute;
        width:100%;
        height:100%;
        background-color: transparent;
        text-align:left;
    }
    .ms_visualizar_produto_descritivo h2{
        color:#194B38;
        font-size:30px;
        margin-bottom:20px;
    }
    .ms_visualizar_produto_descritivo p{
        color:#717171;
        font-size:16px;
        text-align:justify;
        width:100%;
        height:auto;
        font-style: normal;
        margin-top:5px;
    }

</style>

    <div class="w3-row">
        <div class="w3-col s12 ms_visualizar_produto_descritivo">
            <div class="w3-padding">
                <h2><?=utf8_encode($d->prd_produto)?></h2>
                <p><?= utf8_encode($d->prd_descricao)?></p>
            </div>
        </div>
    </div>

<script>
    $(function(){
        Carregando('none');
    })
</script>