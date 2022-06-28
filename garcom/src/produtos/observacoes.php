<?php
    include("../../../lib/includes.php");

    VerificarVendaApp();
?>
<div class="col">
    <div class="col">
        <h4>Incluir Observações</h4>
        <textarea class="form-control" id="observacoes"></textarea>
    </div>
</div>

<div style="position:fixed; bottom:0px; left:0px; width:100%;">
    <button class="btn btn-success btn-lg btn-block" id="incluir_observacoes">Incluir Observações</button>
</div>

<script>
    $(function(){
        Carregando('none');

        function AddKeyboard() {
            windowHeight = window.innerHeight ? window.innerHeight : $(window).height();
            windowWidth = window.innerWidth ? window.innerWidth : $(window).width();

            if (windowWidth > 768) {
                $('#observacoes').keyboard();
            }
        }

        AddKeyboard();

        $("#observacoes").val($(".observacoes").html());

        $("#incluir_observacoes").click(function(){
            $(".observacoes").html($("#observacoes").val());
            PageClose();
        });

    })
</script>