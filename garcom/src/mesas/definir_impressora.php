<?php
    include("../../../lib/includes.php");
?>
<style>
    .PrintTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:65px;
        background:#fff;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }

</style>

<div class="PrintTopoTitulo">
    <h4>
        <i class="fa-solid fa-print"></i> Definir Impressora
    </h4>
</div>

<div class="col">
    <div class="row">
        <div class="col">
            <div acao="terminal1" class="btn btn-success">Caixa</div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div acao="terminal2" class="btn btn-success">Terminal</div>
        </div>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');
        $("div[acao]").click(function(){
            print = $(this).attr("acao");
            window.localStorage.setItem('AppImpressora', print);
            print_html = tipo_impressora[print]
            $.alert(`Impressora padrão definida: <b>${print_html}</b>`);
            PageClose(2);
        });
    })
</script>