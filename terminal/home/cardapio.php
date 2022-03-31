<?php
include("../../lib/includes.php");
?>
<style>
    body{
        background-color: #FFFFFF;
    }

    .cardapio {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 100%;
        overflow: auto;
    }

    .itens<?=$md5?> {
        margin: 0 10px 10px;
    }

    .item_grup<?=$md5?> {
        margin-top: 30px;
    }

    .item_button<?=$md5?> {
        height: 100px;
        text-align: center;
    }

    .item_icone<?=$md5?> {
        font-size: 40px;
    }

    div[foto<?=$md5?>] {
        width: 25%;
        height: 100%;
        background-size: cover;
        background-position: center;
        float: left;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;

    }

    div[texto<?=$md5?>] {
        width: 75%;
        height: 100%;
        text-align: center;
        padding-top: 30px;
        float: right;
        font-size: 26px;
        font-weight: 600;
    }

</style>

<div class="cardapio">
    <h3 style="text-align:center; margin-top: 15px;padding:20px; padding-bottom: 0">
        <i class="fa-brands fa-elementor"></i>
        CARDÁPIO
    </h3>
    <div class="row itens<?= $md5 ?>">
        <?php
        $query = "SELECT * FROM categorias WHERE deletado != '1' AND situacao = '1'";
        $result = mysqli_query($con, $query);
        while ($d = mysqli_fetch_object($result)) { ?>
            <div class="col-md-6 item_grup<?= $md5 ?>">
                <button
                        type="button"
                        class="btn btn-warning btn-block item_button<?= $md5 ?>"
                        categoria="<?= $d->codigo ?>"
                        style="padding:0px; display: table;"
                >
                    <div foto<?= $md5 ?> style="background-image:url(../painel/categorias/icon/<?= $d->icon ?>)"></div>
                    <div texto<?= $md5 ?>><?= $d->categoria ?></div>
                    <!-- <i class="fa-solid fa-martini-glass-citrus item_icone<?= $md5 ?>"></i> -->
                </button>
            </div>
            <?php
        }
        ?>
    </div>
</div>


<div style="position:fixed; left:20px; bottom:20px; display:none">
    <button class="btn btn-danger btn-lg btn-block" sair_venda>SAIR DO TERMINAL</button>
</div>

<?php if ($_SESSION['ConfCliente']): ?>
    <div style="position:fixed; right:40px; bottom:20px;">
        <button type="button" class="btn btn-primary btn-lg btn-block comanda">
            <i class="fa-solid fa-bag-shopping"></i>
        </button>
    </div>
<?php endif; ?>

<script>
    $(function () {

        <?php
        if(!$_SESSION['ConfMesa']){
        ?>
        window.localStorage.clear();
        <?php
        }
        ?>

        ConfMesa = window.localStorage.getItem('ConfMesa');

        if (ConfMesa) {
            $("button[sair_venda]").parent("div").css("display", "block");
        }

        $(".item_button<?=$md5?>").click(function () {

            ConfMesa = window.localStorage.getItem('ConfMesa');

            if (!ConfMesa) {
                JanelaDefineMesa = $.dialog({
                    content: "url:home/definir_mesa.php",
                    title: false,
                    columnClass: "col-md-8 col-md-offset-2",
                    closeIcon: false,
                });
                return false;
            }


            title = $(this).children("p").html();
            categoria = $(this).attr("categoria");

            $.ajax({
                url: "cardapio/produtos.php",
                data: {
                    categoria
                },
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $(".comanda").click(function () {
            $.ajax({
                url: "home/comanda.php",
                success: function (dados) {
                    $("#body").html(dados);
                }
            })
        });




        $("button[sair_venda]").click(function () {
            $.confirm({
                icon: "fa-solid fa-right-from-bracket",
                content: false,
                title: "Deseja realmente sair do terminal?",
                columnClass: "medium",
                type: "red",
                buttons: {
                    'nao': {
                        text: "NÃO, Continuar",
                        action: function () {

                        }
                    },
                    'sim': {
                        text: "Sim, Sair",
                        btnClass: 'btn-red',
                        action: function () {
                            window.localStorage.clear();
                            $.ajax({
                                url: "home/index.php?sair=1",
                                dataType: "JSON",
                                success: function (dados) {
                                    if (dados.status === "erro") {

                                        $.confirm({
                                            icon: "fa-solid fa-right-from-bracket",
                                            content: false,
                                            title: "Você ainda não confirmou seus últimos pedidos para inciarmos o preparo.<br><br>Por favor escolha uma das opções:",
                                            columnClass: "medium",
                                            type: "red",
                                            buttons: {
                                                'nao': {
                                                    text: "Sair mesmo!",
                                                    action: function () {
                                                        $("#body").load("home/index.php?sair=1&confirm=1");
                                                    }
                                                },
                                                'sim': {
                                                    text: "Quero Confirmar",
                                                    action: function () {
                                                        $("#body").load("home/comanda.php");
                                                    }
                                                }
                                            }
                                        })

                                    }else{
                                        $("#body").load("home/index.php");
                                    }
                                }
                            });
                        },
                    }

                }
            });
        });


    })
</script>
