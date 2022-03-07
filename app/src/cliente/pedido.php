<?php
    include("../../../lib/includes.php");
    if($_POST['acao'] == 'SairPedido'){
        $_SESSION = [];
        exit();
    }
    if($_POST['acao'] == 'ExcluirPedido'){
        mysqli_query($con, "update vendas set deletado = '1' where codigo = '{$_SESSION['AppVenda']}'");
        mysqli_query($con, "update vendas_produtos set deletado = '1' where venda = '{$_SESSION['AppVenda']}'");
        $_SESSION = [];
        exit();
    }

    if($_POST['acao'] == 'atualiza'){
        mysqli_query($con, "update vendas_produtos set quantidade='{$_POST['quantidade']}', valor_total='{$_POST['valor_total']}' where codigo = '{$_POST['codigo']}'");
        exit();
    }

    if($_POST['acao'] == 'Excluirproduto'){
        mysqli_query($con, "update vendas_produtos set deletado = '1' where codigo = '{$_POST['codigo']}'");
        $n = mysqli_num_rows("select * from vendas_produtos where venda = '{$_SESSION['AppVenda']}' and deletado != '1'");
        if(!$n) $_SESSION['AppCarrinho'] = false;
        exit();
    }
?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:60px;
        background:#fff;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }
    .PedidoBottomFixo{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        background:#fff;
    }
    .PedidoBottomItens{
        padding:10px;
        text-align:center;
    }
    .PedidoBottomItens button{
        width:calc(100% - 25px);
    }


    .mais{
        position:absolute;
        bottom:0;
        width:50px;
        left:110px;
        font-size:20px;
    }
    .quantidade{
        position:absolute;
        bottom:0;
        width:50px;
        left:60px;
        border:0;
        text-align:center;
        background:transparent !important;
    }
    .menos{
        position:absolute;
        bottom:0;
        width:50px;
        left:10px;
        font-size:20px;
    }

    .rotulo_valor{
        position:absolute;
        right:0px;
        bottom:0px;
        font-size:20px;
        font-weight:bold;
    }


</style>
<div class="PedidoTopoTitulo">
    <h4>Pedido <?=$_SESSION['AppPedido']?></h4>
</div>
<div class="col" style="margin-bottom:60px; margin-top:20px;">
    <div class="col-12">
        <?php
            $query = "select * from vendas_produtos where venda = '{$_SESSION['AppVenda']}' and deletado != '1'";
            $result = mysqli_query($con, $query);
            $valor_total = 0;
            while($d = mysqli_fetch_object($result)){

                $pedido = json_decode($d->produto_json);
                $sabores = false;
                //print_r($pedido)
                $ListaPedido = [];
                for($i=0; $i < count($pedido->produtos); $i++){
                    $ListaPedido[] = $pedido->produtos[$i]->descricao;
                }
                if($ListaPedido) $sabores = implode(', ', $ListaPedido);
        ?>
        <div class="card bg-light mb-3" style="padding-bottom:40px;">
            <div class="card-body">
                <p Excluirproduto codigo="<?=$d->codigo?>" produto="<?=$pedido->categoria->descricao?> - <?=$pedido->medida->descricao?> <?=$sabores?>" style="position:absolute; right:-10px; top:-10px; width:auto;">
                    <i class="fa-solid fa-circle-xmark" style="color:orange; font-size:30px;"></i>
                <p>
                <h5 class="card-title" style="paddig:0; margin:0; font-size:14px; font-weight:bold;">
                    <?=$pedido->categoria->descricao?>
                    - <?=$pedido->medida->descricao?>
                </h5>
                <p class="card-text" style="padding:0; margin:0;">
                    <small class="text-muted"><?=$sabores?></small>
                </p>
                <p class="card-text" style="padding:0; margin:0; text-align:right">
                    R$ <?= number_format($d->valor_unitario, 2, ',', '.') ?>
                </p>
                <p class="card-text" style="padding:0; margin:0; color:red; font-size:10px;">
                    <?= $d->produto_descricao?>
                </p>
                <div cod="<?=$d->codigo?>" style="position:absolute; bottom:0px; left:0px; width:100%;">

                        <button
                                class="btn text-danger menos"
                                type="button"
                        >
                            <i class="fa-solid fa-circle-minus"></i>
                        </button>

                        <div
                                class="form-control quantidade"
                        ><?=$d->quantidade?></div>

                        <button
                                class="btn text-success mais"
                                type="button"
                        >
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>

                        <span
                                class="btn text-primary rotulo_valor"
                        >
                            R$ <span valor atual="<?=$d->valor_unitario?>">
                                <?= number_format($d->valor_total, 2, ',', '.') ?>
                            </span>
                        </span>

                </div>

            </div>
        </div>
        <?php
            $valor_total = ($valor_total + $d->valor_total);
            }
        ?>
    </div>
</div>

<div class="PedidoBottomFixo">
    <div class="row">
        <div class="col-4 PedidoBottomItens">
            <button class="btn btn-danger" ExcluirPedido>
            <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
        <div class="col-8 PedidoBottomItens">
            <button class="btn btn-success" pagar>Pagar <b>R$  <span pedido_valor_toal valor="<?=$valor_total?>"><?= number_format($valor_total, 2, ',', '.') ?></span></b></button>
        </div>
    </div>
</div>


<script>
    $(function(){

        var qt = 0;
        var v_produto_com_sabores = 0;

        $(".mais").click(function () {
            obj = $(this).parent("div");
            codigo = obj.attr('cod');
            quantidade = obj.find(".quantidade").html();
            atual = obj.find("span[valor]").attr("atual");
            quantidade = (quantidade * 1 + 1);
            valortotal = $("span[pedido_valor_toal]").attr("valor");
            obj.find(".quantidade").html(quantidade);
            valor = atual * quantidade;
            valortotal = (valortotal*1 + atual*1);
            $("span[pedido_valor_toal]").attr("valor", valortotal);
            $("span[pedido_valor_toal]").text(valortotal.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            obj.find("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

            $.ajax({
                url:"src/cliente/pedido.php",
                type:"POST",
                data:{
                    quantidade,
                    valor_total:valor,
                    codigo,
                    acao:'atualiza'
                },
                success:function(data){

                }
            });

        });

        $(".menos").click(function () {
            obj = $(this).parent("div");
            codigo = obj.attr('cod');
            quantidade = obj.find(".quantidade").html();
            valortotal = $("span[pedido_valor_toal]").attr("valor");
            atual = obj.find("span[valor]").attr("atual");

            if(quantidade > 1){

                valortotal = (valortotal*1 - atual*1);
                $("span[pedido_valor_toal]").attr("valor", valortotal);
                $("span[pedido_valor_toal]").text(valortotal.toLocaleString('pt-br', {minimumFractionDigits: 2}));


            }

            quantidade = ((quantidade * 1 > 1) ? (quantidade * 1 - 1) : 1);

            obj.find(".quantidade").html(quantidade);
            valor = atual * quantidade;
            obj.find("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

            //if(quantidade > 1){
                $.ajax({
                    url:"src/cliente/pedido.php",
                    type:"POST",
                    data:{
                        quantidade,
                        valor_total:valor,
                        codigo,
                        acao:'atualiza'
                    },
                    success:function(data){

                    }
                });
            //}

        });


        $("button[pagar]").click(function(){
            PageClose();
        });

        $("button[SairPedido]").click(function(){
            $.confirm({
                content:"Deseja realmente cancelar o pedido <b><?=$_SESSION['AppPedido']?></b>?",
                title:false,
                buttons:{
                    'SIM':function(){

                        $.ajax({
                            url:"src/cliente/pedido.php",
                            type:"POST",
                            data:{
                                acao:'SairPedido',
                            },
                            success:function(dados){
                                window.localStorage.removeItem('AppPedido');
                                window.localStorage.removeItem('AppCliente');
                                window.localStorage.removeItem('AppPedido');


                                $.ajax({
                                    url:"src/home/index.php",
                                    success:function(dados){
                                        $(".ms_corpo").html(dados);
                                    }
                                });

                            }
                        });

                    },
                    'NÃO':function(){

                    }
                }
            });


        });

        $("button[ExcluirPedido]").click(function(){

            $.confirm({
                content:"Deseja realmente cancelar o pedido <b><?=$_SESSION['AppPedido']?></b>?",
                title:false,
                buttons:{
                    'SIM':function(){

                        $.ajax({
                            url:"src/cliente/pedido.php",
                            type:"POST",
                            data:{
                                acao:'ExcluirPedido',
                            },
                            success:function(dados){
                                window.localStorage.removeItem('AppPedido');
                                window.localStorage.removeItem('AppCliente');
                                window.localStorage.removeItem('AppPedido');


                                $.ajax({
                                    url:"src/home/index.php",
                                    success:function(dados){
                                        $(".ms_corpo").html(dados);
                                    }
                                });

                            }
                        });

                    },
                    'NÃO':function(){

                    }
                }
            });

        });


        $("p[Excluirproduto]").click(function(){

            produto = $(this).attr('produto');
            codigo = $(this).attr('codigo');
            obj = $(this).parent("div").parent("div");

            quantidade = obj.find(".quantidade").html();
            atual = obj.find("span[valor]").attr("atual");
            desconto = (quantidade * atual);
            valortotal = $("span[pedido_valor_toal]").attr("valor");
            valortotal = (valortotal*1 - desconto*1);


            $.confirm({
                content:"Deseja realmente cancelar o produto <b>"+produto+"</b>?",
                title:false,
                buttons:{
                    'SIM':function(){
                        obj.remove();

                        $("span[pedido_valor_toal]").attr("valor", valortotal);
                        $("span[pedido_valor_toal]").text(valortotal.toLocaleString('pt-br', {minimumFractionDigits: 2}));

                        $.ajax({
                            url:"src/cliente/pedido.php",
                            type:"POST",
                            data:{
                                acao:'Excluirproduto',
                                codigo,
                                produto
                            },
                            success:function(dados){

                            }
                        });

                    },
                    'NÃO':function(){

                    }
                }
            });

        });




    })
</script>