<?php
    include("../../../lib/includes.php");

    VerificarVendaApp();

    if (isset($_POST) and $_POST['acao'] === 'adicionar_pedido') {

        if(!$_SESSION['AppVenda']){
            mysqli_query($con, "INSERT INTO vendas SET cliente = '{$_SESSION['AppCliente']}', mesa = '{$_SESSION['AppPedido']}', atendente = '{$_SESSION['AppGarcom']}', data_pedido = NOW(), situacao = 'producao'");
            $_SESSION['AppVenda'] = mysqli_insert_id($con);
        }

        $arrayInsert = [
            'venda' => $_SESSION['AppVenda'],
            'cliente' => $_SESSION['AppCliente'],
            'atendente' => $_SESSION['AppGarcom'],
            'mesa' => $_SESSION['AppPedido'],
            'produto_descricao' => $_POST['produto_descricao'],
            'quantidade' => $_POST['quantidade'],
            'valor_unitario' => $_POST['valor_unitario'],
            'produto_json' => $_POST['produto_json'],
            'valor_total' => $_POST['valor_total'],
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
        }

        exit();
    }

    $query = "SELECT a.*, b.categoria AS nome_categoria FROM produtos a "
        . "LEFT JOIN categorias b ON a.categoria = b.codigo "
        . "WHERE a.categoria = '8' AND a.deletado != '1' AND b.deletado != '1'";

    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    $detalhes = json_decode($p->detalhes);

    foreach($detalhes as $ind => $val){
        $valor = $val->valor;
        $medida = $val->quantidade;
    }


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
    <center>Sorvete</center>
</div>


<div class="col">
    <div class="row" style="margin-top:10px;">
        <div class="col">

                <!-- <div class="card mb-3">
                    <div class="row">
                        <div
                            class="col foto<?= $md5 ?>"
                            style="background-image:url(../painel/produtos/icon/<?= $p->icon ?>)"
                        >
                            <span sabor><?= $p->produto ?></span>
                            <span categoria><?= $p->nome_categoria ?></span>
                            <span medida><?= $m->medida ?></span>
                            <span val>R$ <?= number_format($p->nome_categoria, 2, ',', '.') ?></span>

                        </div>
                    </div>
                </div> -->

                <div class="row">
                        <div class="col">
                            <div class="card-body">
                                <!-- <h5 class="card-title">
                                    <?= $p->nome_categoria ?> - <?= $p->produto ?> (<?= $m->medida ?>)
                                </h5> -->
                                <p class="card-text"><?= $p->descricao ?></p>
                                <p class="observacoes"></p>
                                <div class="row">
                                    <div class="col-12">
                                        <div style="text-align:right;"><small>R$</small> <small valor_atual><?= number_format($valor, 2, ',', '.') ?></small></div>
                                        <div style="font-size:10px; text-align:right;">Valor por Kg</div>
                                    </div>
                                    <div class="col-6">
                                        <label for="peso">Peso (em gramas)</label>
                                        <input type="number" class="form-control" id="peso">
                                    </div>
                                    <div class="col-6">
                                        <label for="peso">Por Valor</label>
                                        <input type="text" class="form-control" id="custo" data-thousands="" data-decimal=",">
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                <div style="position:fixed; bottom:0; left:0; width:100%; z-index:1; background-color:#fff;">
                    <div class="input-group input-group-lg">
                        <button adicionar_produto class="btn btn-danger btn-lg btn-block">ADICIONAR</button>
                    </div>
                </div>



        </div>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');

        $('#custo').maskMoney();

        $("#peso").keyup(function(){
            peso = $(this).val();
            valor = peso*<?=$valor?>/1000;
            $("#custo").val(valor.toFixed(2));
        });

        $("#custo").keyup(function(){
            custo = $(this).val();
            peso = custo*1000/<?=$valor?>;
            $("#peso").val(peso.toFixed(0));
        });


        $("button[adicionar_produto]").click(function(){
            /////////// PRODUTOS ////////////////////////////

            valor_unitario = $("#custo").val();
            quantidade = 1;
            valor_total = (valor_unitario*quantidade);

            venda = [];
            venda['categoria'] = {codigo:'<?=$p->categoria?>', descricao:'<?=$p->nome_categoria?>'};
            venda['medida'] = {codigo:'<?=$m->codigo?>', descricao:'<?=$m->medida?>'};
            venda['produtos'] = [];
            venda['produtos'].push({codigo:'<?= $p->codigo ?>', descricao:'<?= $p->produto ?>', valor:valor_unitario});

            //-------
            var produto_descricao = $("#peso").val() + 'g (<?= $p->descricao ?>)';

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