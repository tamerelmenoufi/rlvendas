<?php
    include("../../../lib/includes.php");

    if($_POST['cupom']){

        $c = mysqli_fetch_object(mysqli_query($con, "select * from cupom where chave = '{$_POST['cupom']}' and situacao = '1'"));

        if($c->codigo){
            if($c->tipo == 'v'){
                $valor = ($_POST['valor'] - $c->valor);
            }else{
                $valor = ($_POST['valor']/100*(($c->valor)?:1));
            }
            if($valor < $_POST['valor']){
                mysqli_query($con, "update `vendas` set cupom = '{$c->codigo}', cupom_tipo = '{$c->tipo_desconto}', cupom_desconto = '{$c->valor}', cupom_valor = '{$valor}' where codigo = '{$_SESSION['AppVenda']}'");
            }    
        }

    }


    if($_POST['acao'] == 'fechar_conta'){

        $q = "update vendas SET
        situacao = 'pago',
        caixa = (select caixa from caixa where situacao = '0'),
        data_finalizacao = NOW()
        where codigo = '{$_SESSION['AppVenda']}'
        ";
        mysqli_query($con, $q);
        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_SESSION['AppVenda']
            ]
        );

        $q = "update vendas_produtos set situacao = 'c' where venda = '{$_SESSION['AppVenda']}'";
        mysqli_query($con, $q);
        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_SESSION['AppVenda']
            ]
        );

        echo "success";

        exit();
    }

    if($_POST['acao'] == 'acrescimo' or $_POST['acao'] == 'desconto'){
        $q = "update vendas set {$_POST['acao']} = '{$_POST['valor']}' where codigo = '{$_SESSION['AppVenda']}'";
        mysqli_query($con, $q);
        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_SESSION['AppVenda']
            ]
        );
        // exit();
    }

    VerificarVendaApp('delivery');

    if($_SESSION['AppPedido']){
        $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}'"));
    }

    $query = "select
                    sum(a.valor_total) as total,
                    b.nome,
                    b.telefone
                from vendas_produtos a
                    left join clientes b on a.cliente = b.codigo
                where a.venda = '{$_SESSION['AppVenda']}' and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $c = mysqli_fetch_object($result);

//     $q = "update vendas set
//     valor='{$c->total}',
//     taxa='".($c->total/100*10)."',
//     /*desconto='".($c->total/100*10)."',*/
//     total= (".($c->total + ($c->total/100*10) - ($c->total/100*10))." + acrescimo)
// where codigo = '{$_SESSION['AppVenda']}'";

    $q = "update vendas set
        valor='{$c->total}',
        taxa='".($c->total/100*10)."'
    where codigo = '{$_SESSION['AppVenda']}'";
    mysqli_query($con, $q);
    
    sisLog(
        [
            'query' => $q,
            'file' => $_SERVER["PHP_SELF"],
            'sessao' => $_SESSION,
            'registro' => $_SESSION['AppVenda']
        ]
    );

    $query = "select a.*, b.descricao as cupom_descricao from vendas a left join cupom b on a.cupom = b.codigo where a.codigo = '{$_SESSION['AppVenda']}' and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);


    if(!$d->total) $_SESSION['AppCarrinho'] = false;

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
    .card-title small{
        font-size:10px;
    }
    .card-title div{
        width:100%;
        text-align:left;
        font-size:14px;
        font-weight:bold;
    }
    .card-title a{
        width:100%;
        text-align:left;
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
    /* .valor_pendente{
        color:red;
        font-size:14px;
        cursor:pointer;
    }
    .valor{
        font-size:20px;
        color:green;
    } */
    .valor{
        color:red;
        font-size:14px;
        cursor:pointer;
    }

    .formas_pagamento{
        display:none;
    }
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar pedido - <?=$_SESSION['AppVenda']?></h4>
</div>
<?php
$blq = $fechado = false;
$ini = mktime(11, 0, 0, date("m"), date("d"), date("Y"));
$fim = mktime(22, 30, 0, date("m"), date("d"), date("Y"));
$agora = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));

if(!($agora >= $ini and $agora <= $fim)){
    $fechado = true;
}

$query = "select * from clientes where codigo = '{$_SESSION['AppCliente']}'";
$result = mysqli_query($con, $query);
$c = mysqli_fetch_object($result);


$cep = $c->cep;
$logradouro = $c->logradouro;
$numero = $c->numero;
$complemento = $c->complemento;
$ponto_referencia = $c->ponto_referencia;
$bairro = $c->bairro;
$localidade = $c->localidade;
$uf = $c->uf;
$coo = $c->coordenadas;
list($latitude, $longitude) = explode(",",$c->coordenadas);
if($latitude and $longitude){
    $coordenadas = ",
    \"latitude\": ".$latitude.",
    \"longitude\": ".$longitude."
    ";            
}else{
    $coordenadas = false;
}


if(
    !$cep or
    !$logradouro or
    !$numero or
    !$complemento or
    !$bairro
){
    $blq = true;

}


$end = [
    $cep,
    $logradouro,
    $numero,
    $complemento,
    $ponto_referencia,
    $bairro
];

$endereco = [];
foreach($end as $i => $val){
    if($val){
        $endereco[] = $val;
    }
    
}
if($endereco){
    $endereco = implode(", ", $endereco);
}else{
    $endereco = false;
}


$mottu = new mottu;
$json = "{
    \"previewDeliveryTime\": true,
    \"sortByBestRoute\": false,
    \"deliveries\": [
        {
        \"orderRoute\": {$_SESSION['AppVenda']},
        \"address\": {
            \"street\": \"{$logradouro}\",
            \"number\": \"{$numero}\",
            \"complement\": \"{$complemento}\",
            \"neighborhood\": \"{$bairro}\",
            \"city\": \"Manaus\",
            \"state\": \"AM\",
            \"zipCode\": \"".str_replace(array(' ','-'), false, $cep)."\"".$coordenadas."
        },
        \"onlinePayment\": true
        }
    ]
    }";

$mt = $mottu->calculaFrete($json);
$valores = json_decode($mt);

$taxa_entrega = $valores->deliveryFee;

if($taxa_entrega * 1 == 0){
    $blq = true;
}

mysqli_query($con, "update vendas set taxa = '{$taxa_entrega}' where codigo = '{$_SESSION['AppVenda']}'");
?>
<div class="col" style="margin-bottom:60px; display:<?=(($d->total)?'block':'none')?>">
    <div class="row">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Cliente</div>
                <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card-title">
                            <small>Nome</small>
                            <div style="font-size:12px !important; color:#333; font-weight:normal">
                                <?=(($c->nome)?:'Não Registrado')?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="card-title">
                            <small>CPF</small>
                            <div style="font-size:12px !important; color:#333; font-weight:normal"><?=(($c->cpf)?:'Não Registrado')?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card-title">
                            <small>Telefone</small>
                            <div style="font-size:12px !important; color:#333; font-weight:normal"><?=(($c->telefone)?:'Não Registrado')?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-title">
                            <small>Endereço</small>
                            <div style="font-size:12px !important; color:#333; font-weight:normal">
                                <?=(($endereco)?:'Não Registrado')?>
                            </div>
                        </div>
                    </div>
                </div>
                    
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Dados da Compra</div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Taxa de Entrega</small>
                                <div style="font-size:18px !important; color:blue;">R$ <?=number_format($taxa_entrega,2,',',false)?></div>
                            </h5>
                        </div> 
                        <div class="col-6">

                            <?php
                            if($d->cupom){
                            ?>
                            <h5 class="card-title">
                                <small>Desconto Cupom</small>
                                <div style="font-size:18px !important; color:red;">- R$ <?=number_format($d->cupom_valor,2,',',false)?></div>
                            </h5>
                            <?php
                            }
                            ?>
                        </div>     
                        
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Valor da compra</small>
                                <div style="font-size:18px !important; color:blue;">R$ <?=number_format($d->valor,2,',',false)?></div>
                            </h5>
                        </div> 
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Valor a pagar</small>
                                <!-- <div class="valor" valor="<?=$d->valor?>">R$ <?=number_format($d->valor,2,',',false)?></div> -->
                                <div class="valor_pendente" style="font-size:18px !important; color:green;" pendente="" valor="">R$ <?=number_format(($d->valor + $taxa_entrega - $d->cupom_valor),2,',',false)?></div>
                            </h5>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col">
                        <?php
                            if($d->cupom){
                        ?>
                        <div class="alert alert-success m-1" role="alert">
                        <?=$d->cupom_descricao?>
                        </div>
                        <?php
                            }else{
                        ?>
                        Tem Cupom Promocional? Digite aqui!
                        <div class="input-group mb-3">
                            <input type="text" id="cupom" <?=(($_POST['cupom'] and !$d->cupom)?' style="border:solid 1px red" ':false)?> valor="<?=$d->valor?>" class="form-control" placeholder="Digite o códido do cupom" aria-label="Digite o códido do cupom" aria-describedby="inserir_cupom">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" id="inserir_cupom">Validar</button>
                            </div>
                        </div>                     
                        <?php
                            }
                        ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                        <?php
                            $valor_pago = 0;
                            $query = "select * from status_venda where venda = '{$d->codigo}' and retorno->>'$.status' = 'approved'";
                            $result = mysqli_query($con, $query);
                            while($p = mysqli_fetch_object($result)){
                            $op = json_decode($p->retorno);
                            $valor_pago = ($valor_pago + $op->transaction_amount);
                        }
                        ?>
                        </div>
                    </div>
                    <?php
                    $fechado = true;
                    if($fechado){
                    ?>
                    <center>
                        <h3>Pedido não pode ser finalizado</h3>
                        <p>Horário de atendimento no delivery das <?=date("H:i", $ini)?> as <?=date("H:i", $fim)?></p>
                    </center>
                    <?php
                    }else if((($d->valor + $taxa_entrega - $d->cupom_valor) - $valor_pago) > 0 and !$blq){
                    ?>
                    <div class="row">
                        <div class="col">Escolha a forma de pagamento</div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button
                                pagamento="pix"
                                type="button"
                                class="adicionarPagamento btn btn-primary btn-lg btn-block"
                            >
                                <i class="fa fa-qrcode fa-3x"></i><br>
                                R$ <?=number_format((($d->valor + $taxa_entrega - $d->cupom_valor) - $valor_pago),2,',','.')?><br>PIX
                            </button>
                        </div>
                        <div class="col">
                            <button
                                pagamento="credito"
                                type="button"
                                class="adicionarPagamento btn btn-primary btn-lg btn-block"
                            >
                                <i class="fa fa-credit-card fa-3x"></i><br>
                                R$ <?=number_format((($d->valor + $taxa_entrega - $d->cupom_valor) - $valor_pago),2,',','.')?><br>CRÉDITO
                            </button>
                        </div>
                    </div>
                    <?php

                        $q = "update vendas set
                        total= '".(($d->valor + $taxa_entrega - $d->cupom_valor) - $valor_pago)."'
                        where codigo = '{$_SESSION['AppVenda']}'";
                        mysqli_query($con, $q);

                    }else{
                    ?>
                    <center>
                        <h3>Pagamento não pode ser finalizado</h3>
                        <p>Favor atualize seus dados de cadastro e retorno para efetuar o pagamento!</p>
                    </center>
                    <button
                        atualizar_cadastro
                        type="button"
                        class="btn btn-warning btn-lg btn-block"
                    >
                        Ataulizar Cadastro
                    </button>                    
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <?php
    $query = "select * from status_venda where venda = '{$d->codigo}'";
    $result = mysqli_query($con, $query);
    if(mysqli_num_rows($result)){
    ?>

    <div style="display:<?=(($n)?'flex':'none')?>;">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Pagamentos Confirmados</div>
                <div pagar_operacoes class="card-body">

                <?php

                    $query = "select * from status_venda where venda = '{$d->codigo}' and retorno->>'$.status' = 'approved';";
                    $result = mysqli_query($con, $query);
                    while($p = mysqli_fetch_object($result)){

                        $op = json_decode($p->retorno);
                ?>
                    <p>
                        Forma de Pagamento: <?=$p->forma_pagamento?><br>
                        Situação: <?=$op->status?><br>
                        Valor: <?=number_format($op->transaction_amount,2,',','.')?>
                    </p>
                <?php

                    }
                ?>


                    <!-- <h5 class="card-title">
                        <a pagar opc="dinheiro" class="btn btn-success btn-lg"><i class="fa-solid fa-money-bill-1"></i> Dinheiro</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="pix" class="btn btn-success btn-lg"><i class="fa-brands fa-pix"></i> PIX</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="debito" class="btn btn-success btn-lg"><i class="fa-solid fa-credit-card"></i> Débito</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="credito" class="btn btn-success btn-lg"><i class="fa-solid fa-credit-card"></i> Crédito</a>
                    </h5> -->

                </div>
            </div>
        </div>
    </div>

    <?php
    }
    ?>

</div>


<div class="SemProduto" style="display:<?=(($d->valor)?'none':'block')?>">
    <i class="fa-solid fa-face-frown icone"></i>
    <p>Poxa, ainda não tem produtos em seu pedido!</p>
</div>


<script>
    $(function(){


        $("#inserir_cupom").click(function(){
            cupom = $("#cupom").val();
            valor = $("#cupom").attr("valor");
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:`src/produtos/pagar.php`,
                    cupom,
                    valor
                },
                success:function(dados){
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });         
        });


        $("button[pagamento]").click(function(){

            opc = $(this).attr("pagamento");
            valor_total = '<?=(($d->valor + $taxa_entrega - $d->cupom_valor) - $valor_pago)?>';
            AppVenda = '<?=$_SESSION['AppVenda']?>';
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:`src/produtos/pagar_${opc}.php`,
                    valor_total,
                    AppVenda
                },
                success:function(dados){
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });


        });

        
        $("button[fechar_conta]").click(function(){

            $.ajax({
                url:"src/produtos/pagar.php",
                type:"POST",
                data:{
                    acao:`fechar_conta`,
                },
                success:function(dados){
                    if(dados == 'success'){
                        window.localStorage.removeItem('AppPedido');
                        // window.localStorage.removeItem('AppCliente');
                        window.localStorage.removeItem('AppVenda');
                        window.location.href='./?s=1';
                    }
                }
            });


        });

        $("button[atualizar_cadastro]").click(function(){
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:`src/cliente/perfil.php`,
                },
                success:function(dados){
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });
        });


    })
</script>