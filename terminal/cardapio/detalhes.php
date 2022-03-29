<?php
include("../../lib/includes.php");
?>
<style>
    #keyboard_body {
        position: fixed;
        width: 100%;
        top: 40px;
        bottom: 20px;
        z-index: 10;
        display: none;
        background-color: #144766;
    }

    #search_field {
        height: 200px;
    }
</style>

<div id="keyboard_body">
    <div class="row">

        <div class="col-md-12">
            <textarea 
                class="form-control" 
                id="search_field" 
                maxlength="140"
            ></textarea>
            <div id="keyboard"></div>
        </div>

    </div>

    <div style="position:fixed; right:20px; bottom:40px;">
        <button class="btn btn-success btn-lg btn-block" adicionar_detalhes>ADICIONAR</button>
    </div>
    <div style="position:fixed; left:20px; bottom:40px;">
        <button class="btn btn-danger btn-lg btn-block" cancelar_detalhes>CANCELAR</button>
    </div>
</div>

<script>
    $(function() {

        $('#keyboard').jkeyboard({
            layout: "english_capital",
            input: $('#search_field'),
            customLayouts: {
                selectable: ["english_capital"],
                english_capital: [
                    ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', ],
                    ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', ],
                    ['Z', 'X', 'C', 'V', 'B', 'N', 'M'],
                    ['space', 'backspace']
                ],
            }
        });




        $("button[adicionar_detalhes]").click(function() {
            $(".texto_detalhes").text($("#search_field").val());
            $("#keyboard_body").css("display", "none");
        });

        $("button[cancelar_detalhes]").click(function() {
            $("#search_field").val('');
            $(".texto_detalhes").text('');
            $("#keyboard_body").css("display", "none");
        });


    })
</script>