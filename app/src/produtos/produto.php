<?php
    include("../../../lib/includes.php");

    if (isset($_POST) and $_POST['acao'] === 'adicionar_pedido') {

        $arrayInsert = [
            'venda' => $_SESSION['ConfVenda'],
            'cliente' => $_SESSION['ConfCliente'],
            "produto" => $_POST['produto'],
            'quantidade' => $_POST['quantidade'],
            'valor_unitario' => $_POST['valor'],
            'produto_descricao' => $_POST['produto_descricao'],
            'valor_total' => ($_POST['valor'] * $_POST['quantidade']),
            'data' => date('Y-m-d H:i:s'),
        ];

        $attr = [];

        foreach ($arrayInsert as $key => $item) {
            $attr[] = "{$key} = '{$item}'";
        }

        $query = "INSERT INTO vendas_produtos SET " . implode(", ", $attr);

        if (@mysqli_query($con, $query)) {
            echo json_encode([
                "status" => "sucesso",
            ]);
        }

       // exit();
    }

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
        height:250px;
    }
    .foto<?=$md5?> span[sabor]{
        position:absolute;
        left:80px;
        top:20px;
        font-size:16px;
        font-weight:bold;
        color:#333;
        text-shadow:
               -1px -1px 0px #FFF,
               -1px 1px 0px #FFF,
                1px -1px 0px #FFF,
                1px 0px 0px #FFF;
    }
    .foto<?=$md5?> span[categoria]{
        position:absolute;
        right:20px;
        top:45px;
        font-size:16px;
        font-weight:bold;
        color:#333;
        text-shadow:
               -1px -1px 0px #FFF,
               -1px 1px 0px #FFF,
                1px -1px 0px #FFF,
                1px 0px 0px #FFF;
    }
    .foto<?=$md5?> span[medida]{
        position:absolute;
        left:10px;
        bottom:5px;
        font-size:16px;
        font-weight:bold;
        color:#333;
        text-shadow:
               -1px -1px 0px #FFF,
               -1px 1px 0px #FFF,
                1px -1px 0px #FFF,
                1px 0px 0px #FFF;
    }
    .foto<?=$md5?> span[valor]{
        position:absolute;
        right:10px;
        bottom:5px;
        font-size:16px;
        font-weight:bold;
        color:#333;
        text-shadow:
               -1px -1px 0px #FFF,
               -1px 1px 0px #FFF,
                1px -1px 0px #FFF,
                1px 0px 0px #FFF;
    }

    small[valor_novo] {
        display: none;
    }

    .linha_atraves {
        text-decoration-line: line-through;
    }
</style>
<div class="col">
    <div class="row" style="margin-top:-65px;">
        <div class="col">

                <div class="card mb-3">
                    <div class="row">
                        <div
                                class="col foto<?= $md5 ?>"
                                style="background-image:url(../painel/produtos/icon/<?= $p->icon ?>)"
                        >
                            <span sabor><?= $p->produto ?></span>
                            <span categoria><?= $p->nome_categoria ?></span>
                            <span medida><?= $m->medida ?></span>
                            <span valor>R$ <?= number_format($_POST['valor'], 2, ',', '.') ?></span>

                        </div>
                    </div>
                </div>

                <div class="row">
                        <div class="col">
                            <div class="card-body">
                                <!-- <h5 class="card-title">
                                    <?= $p->nome_categoria ?> - <?= $p->produto ?> (<?= $m->medida ?>)
                                </h5> -->
                                <p class="card-text"><?= $p->descricao ?></p>
                                <p style="text-align:right;">
                                    <small valor_atual class="text-muted">
                                        <span>R$ <?= number_format($_POST['valor'], 2, ',', '.') ?></span>
                                        <div style="font-size:10px; margin-top:-20px; text-align:right;">Valor Unitário</div>
                                    </small>

                                </p>


                                <div class="col-md-12" style="margin-bottom:20px;">
                                    <p class="card-text texto_detalhes"></p>
                                </div>
                                <?php if ($m->qt_produtos > 1) { ?>
                                <div class="ListaSabores"></div>

                                <button class="btn btn-primary mais_sabores">Mais Sabores</button>
                                <?php } ?>
                            </div>
                        </div>

                        <div xxx></div>

                    </div>

                <div style="position:fixed; bottom:0; left:0; width:100%; z-index:1;">
                    <div class="input-group input-group-lg">
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
                                R$ <span valor atual="<?=$_POST['valor']?>">
                                    <?= number_format($_POST['valor'], 2, ',', '.') ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>



        </div>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');


        var qt = 0;
        var v_produto_com_sabores = 0;

        $("#mais").click(function () {
            quantidade = $("#quantidade").val();
            atual = $("span[valor]").attr("atual");
            quantidade = (quantidade * 1 + 1);
            $("#quantidade").val(quantidade);
            valor = atual * quantidade;
            $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

        });

        $("#menos").click(function () {
            quantidade = $("#quantidade").val();
            atual = $("span[valor]").attr("atual");
            quantidade = ((quantidade * 1 > 1) ? (quantidade * 1 - 1) : 1);

            $("#quantidade").val(quantidade);

            valor = atual * quantidade;
            $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

        });

        $(".mais_sabores").click(function () {
            produto = '<?=$_POST['produto']?>';
            medida = '<?=$_POST['medida']?>';
            valor = '<?=$_POST['valor']?>';

            Carregando();
            $.ajax({
                url:"componentes/ms_popup.php",
                type:"POST",
                data:{
                    local:"src/produtos/adicionais.php",
                    produto,
                    medida,
                    valor,
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });
        });
    })
</script>