<?php
include("../../lib/includes.php");
?>
<style>
    .comanda {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 40%;
        overflow: auto;
    }

    .itens<?=$md5?> {
        margin: 10px;
    }
</style>

<div class="comanda">

    <div class="row">
        <div class="col-8">
            <?php
            $query = "SELECT vp.codigo AS vp_codigo FROM vendas v "
                . "LEFT JOIN vendas_produtos vp ON vp.venda = v.codigo "
                . "WHERE v.situacao = '0'";
            $result = mysqli_query($con, $query);

            while ($d = mysqli_fetch_object($result)):?>
                <?= $d->vp_codigo; ?>
            <?php endwhile; ?>
        </div>
        <div class="col-4"></div>
    </div>
    <div style="position:fixed; right:20px; bottom:20px;">
        <button sair class="btn btn-danger btn-lg">SAIR</button>
        <button class="btn btn-success btn-lg">CONCLUIR COMPRA</button>
    </div>
</div>


<script>
    $(function () {
        $("button[sair]").click(function () {
            $.ajax({
                url: "home/index.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            })
        });
    })
</script>