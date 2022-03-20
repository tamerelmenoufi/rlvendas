<?php
include("../../lib/includes.php");

if (isset($_POST) and $_POST['acao'] === 'adicionar_pedido') {
    $attr = [];
    $json = [];

    $produto_atual = [
        [
            'codigo' => $_POST['produto'],
            'descricao' => $_POST['produto_descricao'],
            'valor' => $_POST['valor']
        ]
    ];

    $produtos_sabores = $_POST['sabores'] ?: [];

    $produtos = array_merge($produto_atual, $produtos_sabores);

    $json["categoria"] = [
        "codigo " => $_POST['categoria'],
        "descricao" => $_POST['categoria_descricao']
    ];

    $json["medida"] = [
        "codigo" => $_POST['medida'],
        "descricao" => $_POST['medida_descricao']
    ];

    $json["produtos"] = $produtos;

    #file_put_contents("debug.json", json_encode($json));

    // @formatter:off

    foreach ([
                 'venda'             => $_SESSION['ConfVenda'],
                 'cliente'           => $_SESSION['ConfCliente'],
                 'mesa'              => $_SESSION['ConfMesa'],
                 'quantidade'        => $_POST['quantidade'],
                 'valor_unitario'    => $_POST['valor'],
                 'produto_descricao' => $_POST['produto_observacao'],
                 'valor_total'       => ($_POST['valor'] * $_POST['quantidade']),
                 'data'              => date('Y-m-d H:i:s'),
                 'produto_json'      => json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
             ] as $key => $item) {
        $attr[] = "{$key} = '{$item}'";
    }
    // @formatter:on
    $query = "INSERT INTO vendas_produtos SET " . implode(", ", $attr);

    if (@mysqli_query($con, $query)) {
        echo json_encode([
            "status" => "sucesso",
        ]);
    } else {
        file_put_contents('error.txt', mysqli_error($con));
    }

    exit();
}

$produto = $_GET['produto'];
$medida = $_GET['medida'];
$valor = $_GET['valor'];

$query = "SELECT a.*, b.codigo AS cod_categoria, b.categoria AS nome_categoria FROM produtos a "
    . "LEFT JOIN categorias b ON a.categoria = b.codigo WHERE a.codigo = '{$produto}'";

$result = mysqli_query($con, $query);
$p = mysqli_fetch_object($result);

$m = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM categoria_medidas WHERE codigo = '{$medida}'"));

?>

<style>
    .badge-<?= $md5; ?> {
        padding: 5px 12px;
        font-size: 0.85rem;
        color: #fff;
        border-radius: 8px;
        /*text-transform: uppercase;*/
        font-weight: 500;
        line-height: 1;
        white-space: nowrap;
        line-break: normal;
    }

    .cardapio_produto {
        position: absolute;
        left: 0;
        top: 50px;
        bottom: 20px;
        width: 100%;
        overflow: auto;
    }

    .fecharJanelaProduto {
        position: absolute;
        right: 10px;
        bottom: 10px;
    }

    #keyboard {
        background-color: #144766;
    }

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
    }

    small[valor_novo] {
        display: none;
    }

    .linha_atraves {
        text-decoration-line: line-through;
    }
</style>

<div class="cardapio_produto">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div style="position:fixed; top:55px; left:30px; width:<?= (($m->qt_produtos > 1) ? '60%' : 'calc(100% - 60px)') ?>;">
                    <div class="card mb-3">
                        <div class="row">
                            <div
                                    class="col-md-4 foto<?= $md5 ?>"
                                    style="background-image:url('../painel/produtos/icon/<?= $p->icon ?>')"
                            >
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">

                                        <span
                                                class="font-weight-bold h5"
                                                categoria="<?= $p->cod_categoria; ?>"
                                        >
                                            <?= $p->nome_categoria ?>
                                        </span>
                                        - <span
                                                class="font-weight-bold h5"
                                                produto_descricao
                                        ><?= $p->produto ?></span>
                                        (<span
                                                class="font-weight-bold h5"
                                                medida="<?= $m->codigo; ?>"
                                        ><?= $m->medida ?></span>)
                                    </h5>

                                    <p class="card-text mb-1">
                                        <span class="h5"><?= $p->descricao ?></span>
                                    </p>

                                    <p class="card-text d-flex flex-row">
                                        R$ <small valor_atual class="h5 text-muted">
                                            <?= number_format(
                                                $valor,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>
                                        </small>
                                        <small valor_novo class="text-muted ml-2 h5">
                                            R$ 0,00
                                        </small>
                                    </p>

                                    <p class="texto_sabores_adicionais mx-0" style="min-height: 25px;"></p>

                                    <p class="card-text">
                                    <div class="input-group input-group-lg mb-3">
                                        <div class="input-group-prepend">
                                            <button
                                                    class="btn btn-danger"
                                                    type="button"
                                                    id="menos">
                                                <i class="fa-solid fa-circle-minus"></i>
                                            </button>
                                        </div>

                                        <input
                                                type="text"
                                                class="form-control"
                                                id="quantidade"
                                                readonly
                                                value="1"
                                        >

                                        <div class="input-group-append">
                                            <button
                                                    class="btn btn-success"
                                                    type="button"
                                                    id="mais">
                                                <i class="fa-solid fa-circle-plus"></i>
                                            </button>
                                        </div>

                                        <div class="input-group-append">
                                            <span
                                                    class="btn btn-primary"
                                                    id="rotulo_valor">
                                                R$ <span valor="<?= $valor; ?>">
                                                    <?= number_format(
                                                        $valor,
                                                        2,
                                                        ',',
                                                        '.'
                                                    ); ?>
                                                </span>
                                            </span>
                                        </div>

                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-12" style="margin-bottom:20px;">
                            <p class="card-text texto_detalhes"></p>
                        </div>

                        <div class="col-md-12">
                            <button
                                    type="button"
                                    class="btn btn-outline-primary btn-block incluir_detalhes"
                            >
                                INCLUIR RECOMENDAÇÕES
                                <i class="fa-solid fa-hand-pointer"></i>
                            </button>
                        </div>
                    </div>

                    <div style="position:fixed; right:20px; <?= (($m->qt_produtos > 1) ? 'margin-right:calc(40% - 60px);' : false) ?> bottom:30px;">
                        <button
                                class="btn btn-success btn-lg btn-block"
                                adicionar_produto
                                opc="add"
                        >
                            ADICIONAR
                        </button>
                    </div>

                    <div style="position:fixed; left:20px; bottom:30px;">
                        <button
                                class="btn btn-danger btn-lg btn-block"
                                cancelar_produto
                                opc="del"
                        >
                            CANCELAR
                        </button>
                    </div>

                </div>
            </div>

            <!-- Sabores -->
            <div class="col-md-4">
                <?php if ($m->qt_produtos > 1) { ?>
                    <p class="h5 text-center">
                        <b>
                            Você pode adicionar
                            mais <?= ($m->qt_produtos - 1) . ' ' . (($m->qt_produtos == 2) ? 'sabor' : 'sabores') ?>
                        </b>
                    </p>
                    <?php
                    $query = "SELECT a.*, b.categoria AS nome_categoria FROM produtos a "
                        . "LEFT JOIN categorias b ON a.categoria = b.codigo "
                        . "WHERE a.categoria = '{$p->categoria}' "
                        . "AND a.codigo NOT IN ('{$p->codigo}')";

                    $result = mysqli_query($con, $query);

                    while ($p1 = mysqli_fetch_object($result)) :
                        $detalhes = json_decode($p1->detalhes, true);

                        if ($detalhes[$m->codigo]) :
                            $valor_sabores = $detalhes[$m->codigo]['valor'] ?: 0.00;
                            ?>
                            <div class="list-group" style="margin-bottom:10px;">
                                <a
                                        href="#"
                                        class="list-group-item list-group-item-action incluir_sabores"
                                        cod="<?= $p1->codigo; ?>"
                                        descricao="<?= $p1->produto; ?>"
                                        valor="<?= $valor_sabores; ?>"
                                >

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div style="flex: 1">
                                            <span style="font-size: 20px;font-weight: 600"><?= $p1->produto ?></span>
                                        </div>

                                        <div class="text-success font-weight-bold">
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
                        endif;
                    endwhile;
                }
                ?>
            </div>
            <!-- Sabores -->

        </div>
    </div>

    <input type="hidden" id="medida" value="<?= $medida; ?>" readonly>
    <input type="hidden" id="valor" value="<?= $valor; ?>" readonly>

</div>

<script>
    $(function () {

        var qt = 0;

        $.ajax({
            url: "cardapio/detalhes.php",
            success: function (dados) {
                $("#body").append(dados);
            }
        });

        $.ajax({
            url: "home/header.php",
            success: function (dados) {
                $("#body").append(dados);
            }
        });

        $.ajax({
            url: "home/footer.php",
            success: function (dados) {
                $("#body").append(dados);
            }
        });

        $("button[cancelar_produto]").click(function () {
            categoria = '<?=$p->categoria?>';
            opc = $(this).attr("opc");

            $.ajax({
                url: "cardapio/produtos.php",
                data: {
                    categoria,
                },
                success: function (dados) {
                    $("#body").html(dados);
                }
            });
        });

        $(".incluir_detalhes").click(function () {
            $("#keyboard_body").css("display", "block");
        });

        $("#mais").click(function () {
            quantidade = $("#quantidade").val();
            quantidade = (quantidade * 1 + 1);

            $("#quantidade").val(quantidade);

            valor = parseFloat($("span[valor]").attr("valor")) * quantidade;

            $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        });

        $("#menos").click(function () {
            quantidade = $("#quantidade").val();
            quantidade = ((quantidade * 1 > 1) ? (quantidade * 1 - 1) : 1);

            $("#quantidade").val(quantidade);

            valor = Number($("span[valor]").attr("valor")) * quantidade;

            $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        });

        $(".incluir_sabores").click(function () {
            var sabor_codigo = $(this).attr("cod");
            var sabor_descricao = $(this).attr("descricao");

            var obj = $(this);

            if (obj.is(".active")) {
                obj.removeClass("active");
            } else if (qt < (<?=$m->qt_produtos?> - 1)) {
                obj.addClass("active");
            }

            qt = $(".incluir_sabores.active").length;

            if (qt >= 1) {
                let array_valores = [];

                $(".incluir_sabores.active").map((index, item) => array_valores.push(Number($(item).attr("valor"))));

                const valor_max = array_valores.reduce((a, b) => Math.max(a, b));

                if (valor_max > Number($("#valor").val())) {
                    let valor = valor_max * $("#quantidade").val();

                    $("span[valor]")
                        .attr("valor", valor_max)
                        .text(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

                    $("small[valor_atual]").addClass('linha_atraves');

                    $("small[valor_novo]")
                        .text(`R$ ${valor_max.toLocaleString("pt-br", {minimumFractionDigits: 2})}`)
                        .fadeIn(300);

                    $("span[valor]")
                        .text(valor.toLocaleString("pt-br", {minimumFractionDigits: 2}));

                } else {
                    $("small[valor_atual]").removeClass('linha_atraves');
                    $("small[valor_novo]").fadeOut(300);

                    $("span[valor]").attr("valor", Number($("#valor").val()));
                    let valor = Number($("span[valor]").attr("valor")) * Number($("#quantidade").val());
                    $("span[valor]").text(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
                }

            } else {
                $("span[valor]").attr("valor", $("#valor").val());

                let valor = Number($("span[valor]").attr("valor")) * $("#quantidade").val();

                $("span[valor]").text(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

                $('small[valor_atual]').removeClass('linha_atraves');
                $("small[valor_novo]").fadeOut(300);
            }

            if ($(this).is(".active")) {
                let html_badge = `<span id="badge-sabor-${sabor_codigo}" class="animated--fade-in ml-1 badge-<?= $md5; ?> badge-success">`;
                html_badge += `<i class="fa-solid fa-circle-plus"></i> ${sabor_descricao}</span>`;

                $(".texto_sabores_adicionais").append(html_badge);
            } else {
                $(`#badge-sabor-${sabor_codigo}`).remove();
            }
        });

        $("button[adicionar_produto]").click(function () {

            // @formatter:off
            var produto_observacao  = $("#search_field").val();
            var produto_descricao   = $("span[produto_descricao]").text().trim();
            var quantidade          = $("#quantidade").val();
            var valor               = Number($("span[valor]").attr("valor"));
            var medida              = $("#medida").val();
            var medida_descricao    = $("span[medida]").text().trim();
            var categoria           = '<?=$p->categoria?>';
            var categoria_descricao = $("span[categoria]").text().trim();
            // @formatter:on

            let obj_sabores = $(".incluir_sabores.active");

            var sabores = [];

            if (obj_sabores.length >= 1) {
                obj_sabores.map((index, item) => {
                    let codigo = Number($(item).attr("cod"));
                    let descricao = $(item).attr("descricao");
                    let valor = Number($(item).attr("valor"));

                    sabores.push({"codigo": codigo, "descricao": descricao, "valor": valor});
                });
            }

            /*$.alert({
                title: "Confirmar pedido?",
                content: false,
                icon: 'fa-solid fa-question',
                type: "red",
                buttons: {
                    sim: {
                        text: "Sim",
                        btnClass: 'btn-red',
                        action: function () {

                        }
                    },
                    nao: function () {
                    }
                }
            });*/

            console.log(produto_descricao);

            $.ajax({
                url: "cardapio/produto.php",
                method: 'POST',
                dataType: 'JSON',
                data: {
                    quantidade,
                    produto_descricao,
                    produto_observacao,
                    valor,
                    sabores,
                    categoria,
                    categoria_descricao,
                    medida,
                    medida_descricao,
                    acao: 'adicionar_pedido',
                },
                success: function (dados) {
                    if (dados.status === 'sucesso') {
                        opc = $(this).attr("opc");

                        $.ajax({
                            url: "cardapio/produtos.php",
                            data: {
                                categoria,
                            },
                            success: function (dados) {
                                tata.success('Sucesso', 'Pedido adicionado com sucesso');
                                $("#body").html(dados);
                            }
                        });
                    }
                }
            });
        });
    });
</script>