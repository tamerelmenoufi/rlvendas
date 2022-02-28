<?php
    include("../../../lib/includes.php");

    $produto = $_POST['produto'];
    $medida = $_POST['medida'];
    $valor = $_POST['valor'];

    $query = "SELECT a.*, b.categoria AS nome_categoria FROM produtos a "
        . "LEFT JOIN categorias b ON a.categoria = b.codigo "
        . "WHERE a.codigo = '{$produto}'";

    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    $m = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM categoria_medidas WHERE codigo = '{$medida}'"));


?>
<style>
    span[valor] {
        margin-left: 10px;
    }

    #quantidade {
        text-align: center;
    }

    #rotulo_valor {
        width: 180px;
        font-weight: bold;
    }

    .texto_detalhes {
        color: red;
        font-size: 12px;
    }

    .foto<?=$md5?> {
        background-size: cover;
        background-position: center;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        height:200px;
    }

    small[valor_novo] {
        display: none;
    }

    .linha_atraves {
        text-decoration-line: line-through;
    }
</style>
<div class="col">
    <div class="row">
        <div class="col">
            <?php if ($m->qt_produtos > 1) { ?>
                <center>
                    <b>
                        Você pode adicionar
                        mais <?= ($m->qt_produtos - 1) . ' ' . (($m->qt_produtos == 2) ? 'sabor' : 'sabores') ?>
                    </b>
                </center>
                <?php
                $query = "SELECT a.*, b.categoria AS nome_categoria FROM produtos a "
                    . "LEFT JOIN categorias b ON a.categoria = b.codigo "
                    #. "WHERE /*a.categoria = '{$p->categoria}' and*/ "
                    . "WHERE a.codigo NOT IN ('{$p->codigo}')";

                $result = mysqli_query($con, $query);
                while ($p1 = mysqli_fetch_object($result)) {
                    $detalhes = (array)json_decode($p1->detalhes, true);

                    if ($detalhes[$medida]) {
                        $valor_sabores = $detalhes[$medida][0] ?: 0.00;
                        ?>
                        <div class="list-group" style="margin-bottom:10px;">
                            <a
                                    href="#"
                                    class="list-group-item list-group-item-action add_sabores"
                                    valor="<?= $valor_sabores; ?>"
                                    cod="<?= $p1->codigo; ?>"
                                    nome="<?= $p1->produto ?>"
                            >
                                <div class="d-flex justify-content-between">
                                    <div nome style="flex: 1">
                                        <?= $p1->produto ?>
                                    </div>
                                    <div>
                                        R$ <?= number_format(
                                            $valor_sabores,
                                            '2',
                                            ',',
                                            '.'
                                        ); ?>
                                    </div>
                                </div>
                            </a>
                        </div>


                        <?php
                    }
                }
            }
            ?>
        </div>

    </div>
</div>
<script>
    $(function(){

        Carregando('none');
        var qt = 0;
        var v_produto_com_sabores = 0;


        $(".ListaSabores div").each(function(){
            codigo = $(this).attr("cod");
            $('.add_sabores[cod="'+codigo+'"]').addClass('active');
        });


        $(".add_sabores").click(function () {
            let valor_sabor = Number($(this).attr('valor'));

            if ($(this).is(".active")) {
                $(this).removeClass("active");
            } else if (qt < (<?=$m->qt_produtos?> - 1)) {
                $(this).addClass("active");
            }

            qt = $(".add_sabores.active").length;

            if (qt >= 1) {
                $("small[valor_atual]").addClass('linha_atraves');
                v_produto_com_sabores += valor_sabor;
                $("small[valor_novo]").text(`R$ ${v_produto_com_sabores.toLocaleString('pt-br', {minimumFractionDigits: 2})}`);
                $("small[valor_novo]").fadeIn(300);
            } else {
                $('small[valor_atual]').removeClass('linha_atraves');
                $("small[valor_novo]").fadeOut(300);
                v_produto_com_sabores = 0;
            }

            ValorMaior = Number('<?=$valor?>');
            var Lista = '';

            $(".add_sabores.active").each(function(){
                valor_produto = Number($(this).attr("valor"));
                nome_produto = $(this).attr("nome");
                codigo_produto = $(this).attr("cod");

                if(valor_produto > ValorMaior){
                    ValorMaior = valor_produto;
                }

                Lista += '<div class="list-group" style="margin-bottom:5px;" cod="'+codigo_produto+'">' +
                        '<a href="#" class="list-group-item list-group-item-action">' +
                        nome_produto +
                        '</a>' +
                        '</div>';

            });

            $("span[valor]").attr("atual", ValorMaior);
            quantidade = $("#quantidade").val();
            valor = ValorMaior * quantidade;
            $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            $("small[valor_atual] span").html(ValorMaior.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            $(".ListaSabores").html(Lista);

        });



    })
</script>