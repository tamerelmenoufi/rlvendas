<?php
    include("../../../lib/includes.php");

    VerificarVendaApp('delivery');

    if (isset($_POST) and $_POST['acao'] === 'adicionar_pedido') {



        if(!$_SESSION['AppVenda']){
            $q = "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', atendente = '{$_SESSION['AppGarcom']}', data_pedido = NOW(), situacao = 'producao'";
            mysqli_query($con, $q);
            $_SESSION['AppVenda'] = mysqli_insert_id($con);
            sisLog(
                [
                    'query' => $q,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => $_SESSION['AppVenda']
                ]
            );
        }


        $q1 = "SELECT *, retorno->>'$.id' as id FROM `status_venda` where venda = '{$_SESSION['AppVenda']}' and retorno->>'$.status' = 'pending'";
        $r1 = mysqli_query($con, $q1);
        while($d1 = mysqli_fetch_object($r1)){
            $PIX = new MercadoPago;
            $rt = $PIX->CancelarPagamento($d1->id);
            mysqli_query($con, "update status_venda set retorno = '{$rt}' where venda = '{$_SESSION['AppVenda']}' and retorno->>'$.id' = '{$d1->id}'");
        }

        $quantidade = (($_POST['quantidade']*1 >= 1)?$_POST['quantidade']:1);
        $total = $_POST['valor_unitario'] * $quantidade;

        $arrayInsert = [
            'venda' => $_SESSION['AppVenda'],
            'app' => $localApp,
            'cliente' => $_SESSION['AppCliente'],
            'atendente' => $_SESSION['AppGarcom'],
            'mesa' => $_SESSION['AppPedido'],
            'produto_descricao' => $_POST['produto_descricao'],
            'quantidade' => $quantidade,
            'valor_unitario' => $_POST['valor_unitario'],
            'produto_json' => $_POST['produto_json'],
            'valor_total' => $total,
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
            $_SESSION['AppCarrinho'] = true;
            sisLog(
                [
                    'query' => $query,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => mysqli_insert_id($con)
                ]
            );
        }

        mysqli_query($con, "update `vendas` set cupom = 0, cupom_tipo = '', cupom_valor = 0 where codigo = '{$_SESSION['AppVenda']}'");

        exit();
    }

    $produto = $_POST['produto'];
    $medida = $_POST['medida'];
    $valor = $_POST['valor'];

    $query = "SELECT a.*, b.categoria AS nome_categoria FROM produtos a "
        . "LEFT JOIN categorias b ON a.categoria = b.codigo "
        . "WHERE a.codigo = '{$produto}' AND a.deletado != '1' AND b.deletado != '1'";

    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    $m = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM categoria_medidas WHERE codigo = '{$medida}' AND deletado != '1'"));


?>
<style>

    .topo<?=$md5?> {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 55px;
        background-color: #fff;
        padding: 20px;
        font-weight: bold;
        z-index: 1;
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
        height:250px;
        background-color:#ccc;
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
        display:none;
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
        display:none;
        
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
        display:none;
    }
    .foto<?=$md5?> span[val]{
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
        display:none;
    }

    small[valor_novo] {
        display: none;
    }

    .linha_atraves {
        text-decoration-line: line-through;
    }
    .ListaSabores{
        margin-bottom:100px;
    }
    .observacoes{
        color:red;
        font-size:10px;
        /* text-align:justify; */
    }
</style>

<div class="topo<?= $md5 ?>">
    <div class="topo_interno<?=$md5?>">Detalhes Produto</div>
</div>


<div class="col">
    <div class="row">
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
                            <span val>R$ <?= number_format($_POST['valor'], 2, '.', false) ?></span>

                        </div>
                    </div>
                </div>

                <div class="row">
                        <div class="col">
                            <div class="card-body">
                                <h5 class="card-title" style="color:#a80e13; font-size:14px;">
                                    <?= $p->nome_categoria ?> - <?= $p->produto ?> (<?= $m->medida ?>)
                                </h5>
                                <p class="card-text" style="font-size:12px;"><?= $p->descricao ?></p>
                                <p class="observacoes"></p>
                                <div class="row">
                                <div class="col-8">
                                    <button observacoes class="btn btn-warning btn-block"><i class="fa-solid fa-pencil"></i> Recomendações</button>
                                </div>
                                <div class="col-4">
                                    <div style="text-align:right;"><small>R$</small> <small valor_atual><?= number_format($_POST['valor'], 2, '.', false) ?></small></div>
                                    <div style="font-size:10px; text-align:right;">Valor Cobrado</div>
                                </div>
                                </div>


                                <div class="col-md-12" style="margin-bottom:20px;">
                                    <p class="card-text texto_detalhes"></p>
                                </div>
                                <?php if ($m->qt_produtos > 1) { ?>
                                <button class="btn btn-primary btn-block mais_sabores" style="margin-bottom:5px;">
                                    Pode adicionar até mais
                                    <?= ($m->qt_produtos - 1) . ' ' . (($m->qt_produtos == 2) ? 'sabor' : 'sabores') ?>
                                </button>
                                <div class="ListaSabores"></div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                <div style="position:fixed; bottom:70px; left:0; width:100%; z-index:1; background-color:#fff;">
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <button
                                    class="btn btn-dangerX text-danger"
                                    type="button"
                                    id="menos">
                                <i class="fa-solid fa-circle-minus"></i>
                            </button>
                        </div>

                        <div
                                class="form-control"
                                id="quantidade"
                                style="border:0;"
                        >1</div>

                        <div class="input-group-append">
                            <button
                                    class="btn btn-successX text-success"
                                    type="button"
                                    id="mais">
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </div>
                        <div class="input-group-append">
                        <button adicionar_produto class="btn btn-danger" style="width:200px;">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span
                                    classX="btn btn-primaryX text-primary"
                                    id="rotulo_valor">
                                R$ <span valor atual="<?=$_POST['valor']?>">
                                    <?= number_format($_POST['valor'], 2, '.', false) ?>
                                </span>
                            </span>
                        </button>
                        </div>
                    </div>
                    <!-- <div class="input-group input-group-lg">
                        <button adicionar_produto class="btn btn-danger btn-lg btn-block">ADICIONAR</button>
                    </div> -->
                </div>



        </div>
    </div>
</div>
<div class="rodape<?= $md5 ?>"></div>
<script>
    $(function(){
        Carregando('none');

        $.ajax({
            url:"componentes/ms_rodape.php",
            success:function(dados){
                $(".rodape<?=$md5?>").html(dados);
            }
        });

        $.ajax({
            url:"componentes/ms_topo_interno.php",
            type:"POST",
            data:{
                titulo:$(".topo_interno<?=$md5?>").text(),
            },
            success:function(dados){
                $(".topo_interno<?=$md5?>").html(dados);
            }
        });

        var qt = 0;
        var v_produto_com_sabores = 0;

        $("#mais").click(function () {
            quantidade = $("#quantidade").html();
            atual = $("span[valor]").attr("atual");
            quantidade = (quantidade * 1 + 1);
            $("#quantidade").html(quantidade);
            valor = atual * quantidade;
            // $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            $("span[valor]").html(valor.toFixed(2));

        });

        $("#menos").click(function () {
            quantidade = $("#quantidade").html();
            atual = $("span[valor]").attr("atual");
            quantidade = ((quantidade * 1 > 1) ? (quantidade * 1 - 1) : 1);

            $("#quantidade").html(quantidade);

            valor = atual * quantidade;
            // $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            $("span[valor]").html(valor.toFixed(2));

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

        $("button[observacoes]").click(function(){
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:"src/produtos/observacoes.php",
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });
        });


        $("button[adicionar_produto]").click(function(){
            /////////// PRODUTOS ////////////////////////////
            venda = [];
            venda['categoria'] = {codigo:'<?=$p->categoria?>', descricao:'<?=$p->nome_categoria?>'};
            venda['medida'] = {codigo:'<?=$m->codigo?>', descricao:'<?=$m->medida?>'};
            venda['produtos'] = [];
            venda['produtos'].push({codigo:'<?= $p->codigo ?>', descricao:'<?= $p->produto ?>', valor:'<?= $_POST['valor'] ?>'});
            $('.grupo').each(function(){
                venda['produtos'].push({codigo:$(this).attr("cod"), descricao:$(this).attr("nome"), valor:$(this).attr("valor")});
            })

            //-------
            valor_unitario = $("span[valor]").attr("atual");
            //-------
            quantidade = $("#quantidade").html();
            //-------
            valor_total = (valor_unitario*quantidade);

            //-------
            var produto_descricao = $(".observacoes").html();

            var produto_json = JSON.stringify(Object.assign({}, venda));
            $(".IconePedidos, .MensagemAddProduto").css("display","none");
            $.ajax({
                url:"src/produtos/produto.php",
                type:"POST",
                data:{
                    produto_json,
                    produto_descricao,
                    valor_unitario,
                    quantidade,
                    valor_total,
                    acao:'adicionar_pedido'
                },
                success:function(dados){
                    PageClose();
                    $(".IconePedidos, .MensagemAddProduto").css("display","block");
                    setTimeout(function(){
                        $(".MensagemAddProduto").css('display','none');
                    }, 3000);
                }
            });

        });

    })
</script>