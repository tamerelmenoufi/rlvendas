<?php
include("../../lib/includes.php");
$id = $_GET['id'];
$valor = $_GET['valor'] ?: "";

?>
<style>
    #keyboard_body {
        width: 100%;
        background-color: #144766;
    }

    #search_field {
        height: 80px;
    }
</style>

<div id="keyboard_body">
    <div class="col-md-12">
        <br>
        <textarea
                class="form-control"
                id="search_field-<?= $id; ?>"
                autofocus
        ><?= $valor; ?></textarea>
    </div>
    <div id="keyboard-<?= $id; ?>"></div>
</div>

<script>
    $(function () {

        $('#keyboard-<?= $id; ?>').jkeyboard({
            layout: "english_capital",
            input: $('#search_field-<?= $id;?>'),
            customLayouts: {
                selectable: ["english_capital"],
                english_capital: [
                    ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P',],
                    ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L',],
                    ['Z', 'X', 'C', 'V', 'B', 'N', 'M'],
                    ['space', 'backspace']
                ],
            }
        });

        $("button[adicionar_detalhes]").click(function () {
            $(".texto_detalhes").text($("#search_field").val());
            $("#keyboard_body").css("display", "none");
        });

        $("button[cancelar_detalhes]").click(function () {
            $("#search_field").val('');
            $(".texto_detalhes").text('');
            $("#keyboard_body").css("display", "none");
        });
    });
</script>