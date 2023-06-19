<?php
    include("../../../lib/includes.php");

    VerificarVendaApp();

    if($_SESSION['AppGarcom']){
        $query = "select * from atendentes where codigo = '{$_SESSION['AppGarcom']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);
        $_SESSION['AppPerfil'] = json_decode($d->perfil);
    }




    if($_SESSION['AppPedido']){
        $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}'"));
    }


    if (!empty($_POST) and $_POST["acao"] === "confirmar_pedido") {

        $codigo = $_SESSION['AppVenda'];
        $codigos = [];
        $query = "SELECT * FROM vendas_produtos WHERE venda = '{$codigo}' and situacao = 'n'";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
            $codigos[] = $d->codigo;
        }
        $codigos = implode(",", $codigos);

        $ordem = strtotime("now");

        $query = "UPDATE vendas_produtos SET situacao = 'p', ordem = '{$ordem}' WHERE codigo in ({$codigos})";
        if (mysqli_query($con, $query)) {
            echo json_encode([
                "status" => "sucesso",
                "venda" => base64_encode($codigos),
            ]);
        }
        mysqli_query($con, "update vendas set situacao = 'preparo' where codigo = '{$codigo}'");
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


// Script para definir o valor total da venda e a taxa de serviços
        $query = "select
        sum(a.valor_total) as total,
        b.nome,
        b.telefone
        from vendas_produtos a
        left join clientes b on a.cliente = b.codigo
        where a.venda = '{$_SESSION['AppVenda']}' and a.deletado != '1'";
        $result = mysqli_query($con, $query);
        $c = mysqli_fetch_object($result);

        $q = "update vendas set
        valor='{$c->total}',
        taxa='".($c->total/100*10)."',
        total= (".($c->total + ($c->total/100*10))." + acrescimo - desconto)
        where codigo = '{$_SESSION['AppVenda']}'";
        mysqli_query($con, $q);
// Fim do script




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
        padding:5px;
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

    .SemProduto{
        position:fixed;
        top:40%;
        left:0;
        text-align:center;
        width:100%;
        color:#ccc;
    }
    .icone{
        font-size:70px;
    }
    p[Tempo]{
        position:absolute;
        right:30px;
        top:5px;
        width:auto;
        font-size:11px;
        color:red;
        font-weight:bold;
    }
    p[Garcom]{
        position:absolute;
        right:30px;
        top:20px;
        width:auto;
        font-size:11px;
        color:blue;
        font-weight:normal;
        display:<?=(($_SESSION['AppGarcom'] == 3 or $_SESSION['AppGarcom'] == 10)?'block':'none')?>;
    }
</style>
<div class="PedidoTopoTitulo">
    <h4>Pedido Mesa <?=$m->mesa?></h4>
</div>
<div class="col" style="margin-bottom:60px; margin-top:20px;">
    <div class="col-12">
        <?php
            $query = "select a.*,
                        b.nome as atendente
                        from vendas_produtos a
                        left join atendentes b on a.atendente = b.codigo
                    where a.venda = '{$_SESSION['AppVenda']}' and a.deletado != '1' order by a.codigo desc";
            $result = mysqli_query($con, $query);
            $valor_total = 0;
            $n = mysqli_num_rows($result);

            $acao_preparar = false;
            $acao_cancelar = true;

            while($d = mysqli_fetch_object($result)){

                $pedido = json_decode($d->produto_json);
                $sabores = false;
                //print_r($pedido)
                $ListaPedido = [];
                for($i=0; $i < count($pedido->produtos); $i++){
                    $ListaPedido[] = $pedido->produtos[$i]->descricao;
                }
                if($ListaPedido) $sabores = implode(', ', $ListaPedido);


                if($d->situacao != 'n'){
                    $blq = 'display:none;';
                    $acao_cancelar = false;

                }else{
                    $blq = false;
                    $acao_preparar = true;
                }

                if(!$_SESSION['AppPerfil'][0]->value and $d->situacao != 'n'){
                    $blqc = 'display:none;';

                }else{
                    $blqc = false;
                }


        ?>
        <div class="card bg-light mb-3" style="padding-bottom:40px;">
            <div class="card-body">
                <p Excluirproduto codigo="<?=$d->codigo?>" produto="<?=$pedido->categoria->descricao?> - <?=$pedido->medida->descricao?> <?=$sabores?>" style="position:absolute; right:-10px; top:-10px; width:auto;">
                    <i class="fa-solid fa-circle-xmark" style="color:orange; font-size:30px; <?=$blqc?>"></i>
                <p>
                <p Tempo>
                    <?=CalcTempo($d->data)?>
                </p>
                <p Garcom>
                    <?=$d->atendente?>
                </p>
                <h5 class="card-title" style="paddig:0; margin:0; font-size:14px; font-weight:bold;">
                    <?=$pedido->categoria->descricao?>
                    <?=(($pedido->medida->descricao)?' - '.$pedido->medida->descricao:false)?>
                </h5>
                <p class="card-text" style="padding:0; margin:0;">
                    <small class="text-muted"><?=$sabores?></small>
                </p>
                <p class="card-text" style="padding:0; margin:0; text-align:right">
                    R$ <?= number_format($d->valor_unitario, 2, '.', false) ?>
                </p>
                <p class="card-text" style="padding:0; margin:0; color:red; font-size:10px;">
                    <?= $d->produto_descricao?>
                </p>
                <div cod="<?=$d->codigo?>" style="position:absolute; bottom:0px; left:0px; width:100%;">

                        <button
                                class="btn text-danger menos"
                                type="button"
                                style="<?=$blq?>"
                        >
                            <i class="fa-solid fa-circle-minus"></i>
                        </button>

                        <div
                                class="form-control quantidade"
                        ><?=$d->quantidade?></div>

                        <button
                                class="btn text-success mais"
                                type="button"
                                style="<?=$blq?>"
                        >
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>

                        <span
                                class="btn text-primary rotulo_valor"
                        >
                            R$ <span valor atual="<?=$d->valor_unitario?>">
                                <?= number_format($d->valor_total, 2, '.', false) ?>
                            </span>
                        </span>

                </div>

            </div>
        </div>
        <?php
            $valor_total = ($valor_total + $d->valor_total);
            }
        ?>

        <div class="SemProduto" style="display:<?=(($n)?'none':'block')?>">
            <i class="fa-solid fa-face-frown icone"></i>
            <p>Poxa, ainda não tem produtos em seu pedido!</p>
        </div>

    </div>
</div>

<div class="PedidoBottomFixo">
    <div class="row">
        <div class="col-3">
            <button
                class="btn btn-danger btn-block"
                ExcluirPedido
                style="<?=((!$acao_cancelar)?'display:none;':false)?>"
            >
            <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
        <div class="col-2">
            <button
                confirmar_pedido
                class="btn btn-primary btn-block"
                style="<?=((!$acao_preparar)?'display:none;':false)?>"
            >
                ok
            </button>
        </div>
        <div class="col-2">
            <button
                print_pedido
                class="btn btn-warning btn-block"
                <?=((!$valor_total)?'disabled':false)?>
            >
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
        <div class="col-5">
            <button <?=((!$valor_total)?'disabled':false)?> class="btn btn-success btn-block" pagar>Pagar <b>R$  <span pedido_valor_toal valor="<?=$valor_total?>"><?= number_format($valor_total, 2, '.', false) ?></span></b></button>
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
            // $("span[pedido_valor_toal]").text(valortotal.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            // obj.find("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

            $("span[pedido_valor_toal]").text(valortotal.toFixed(2));
            obj.find("span[valor]").html(valor.toFixed(2));


            $.ajax({
                url:"src/produtos/pedido.php",
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
                // $("span[pedido_valor_toal]").text(valortotal.toLocaleString('pt-br', {minimumFractionDigits: 2}));
                $("span[pedido_valor_toal]").text(valortotal.toFixed(2));


            }

            quantidade = ((quantidade * 1 > 1) ? (quantidade * 1 - 1) : 1);

            obj.find(".quantidade").html(quantidade);
            valor = atual * quantidade;
            // obj.find("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            obj.find("span[valor]").html(valor.toFixed(2));

            //if(quantidade > 1){
                $.ajax({
                    url:"src/produtos/pedido.php",
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








        $("button[confirmar_pedido]").click(function () {

            $.alert({
                icon: "fa-solid fa-question",
                title: "Seu pedido será enviado para o preparo após a sua confirmação.<br><br>Deseja confirmar o envio?",
                content: false,
                columnClass: "medium",
                type: "green",
                buttons: {
                    nao: {
                        text: "Não",
                        btnClass: "btn-red",
                        action: function () {
                        }
                    },
                    sim: {
                        text: "Sim, Pode Preparar",
                        btnClass: "btn-success",
                        action: function () {


                            $.ajax({
                                url: "src/produtos/pedido.php",
                                method: "POST",
                                dataType: "JSON",
                                data: {
                                    acao: "confirmar_pedido"
                                },
                                success: function (dados) {
                                    if (dados.status === "sucesso") {
                                        atualiza = dados.venda;


                                        $.ajax({
                                            url:"componentes/ms_popup_100.php",
                                            type:"POST",
                                            data:{
                                                local:'src/produtos/pedido.php',
                                            },
                                            success:function(dados){
                                                PageClose();
                                                $(".ms_corpo").append(dados);
                                                mySocket.send(atualiza);
                                                console.log(atualiza);
                                            }
                                        });



                                    }
                                }
                            })






                        }
                    },
                }
            });

        });







        $("button[pagar]").click(function(){
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:'src/produtos/pagar.php',
                },
                success:function(dados){
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });
        });


        $("button[ExcluirPedido]").click(function(){

            $.confirm({
                content:"Deseja realmente cancelar o pedido <b><?=$m->mesa?></b>?",
                title:false,
                buttons:{
                    'SIM':function(){

                        $.ajax({
                            url:"src/produtos/pedido.php",
                            type:"POST",
                            data:{
                                acao:'ExcluirPedido',
                            },
                            success:function(dados){
                                window.localStorage.removeItem('AppPedido');
                                //window.localStorage.removeItem('AppCliente');
                                window.localStorage.removeItem('AppVenda');

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

            n = $("p[Excluirproduto]").length;


            JanelaConfirmacao = $.confirm({
                content:"Deseja realmente cancelar o produto <b>"+produto+"</b>?",
                title:false,
                buttons:{
                    'SIM':function(){

                        obj.remove();

                        if(n === 1){
                            $(".SemProduto").css("display","block");
                            $("button[pagar]").attr("disabled","disabled");
                            $("button[confirmar_pedido]").attr("disabled","none");
                        }

                        $("span[pedido_valor_toal]").attr("valor", valortotal);
                        // $("span[pedido_valor_toal]").text(valortotal.toLocaleString('pt-br', {minimumFractionDigits: 2}));
                        $("span[pedido_valor_toal]").text(valortotal.toFixed(2));

                        $.ajax({
                            url:"src/produtos/pedido.php",
                            type:"POST",
                            data:{
                                acao:'Excluirproduto',
                                codigo,
                                produto
                            },
                            success:function(dados){
                                mySocket.send('atualiza');
                                JanelaConfirmacao.close();
                            }
                        });


                    },
                    'NÃO':function(){

                    }
                }
            });

        });



        $("button[print_pedido]").click(function(){
            $.confirm({
                content:"Confirma a Impressão da comanda?",
                title:false,
                buttons:{
                    'SIM':function(){
                        Carregando();
                        impressora = window.localStorage.getItem('AppImpressora');
                        $.ajax({
                            url:"src/produtos/print.php",
                            type:"POST",
                            data:{
                                impressora
                            },
                            success:function(dados){
                                $.alert('Comanda enviada para impressão!');
                                Carregando('none');
                            }
                        });
                    },
                    'NÃO':function(){

                    }
                }
            })
        });



    })
</script>