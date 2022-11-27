<?php
    include("../../../lib/includes.php");

    $query = "SELECT
        v.*,
        m.mesa
    FROM vendas v
    left join mesas m on v.mesa = m.codigo
    where v.codigo = '{$_POST['venda']}'
    ";
    $result = mysqli_query($con, $query);
    $v = mysqli_fetch_object($result);

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
    <h4>Mesa <?=$v->mesa?> - Pedido #<?=str_pad($v->codigo, 5, "0", STR_PAD_LEFT)?></h4>
</div>
<div class="col" style="margin-bottom:60px; margin-top:20px;">
    <div class="col-12">
        <?php
            $query = "select a.*,
                        b.nome as atendente
                        from vendas_produtos a
                        left join atendentes b on a.atendente = b.codigo
                    where a.venda = '{$v->codigo}' order by a.codigo desc";
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
                <!-- <p Excluirproduto codigo="<?=$d->codigo?>" produto="<?=$pedido->categoria->descricao?> - <?=$pedido->medida->descricao?> <?=$sabores?>" style="position:absolute; right:-10px; top:-10px; width:auto;">
                    <i class="fa-solid fa-circle-xmark" style="color:orange; font-size:30px; <?=$blqc?>"></i>
                <p> -->
                <!-- <p Tempo>
                    <?=CalcTempo($d->data)?>
                </p> -->
                <p Garcom>
                    <?=$d->atendente?>
                </p>
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
        <div class="col-5">
            <button nota_fiscal="<?=$v->codigo?>" opc="<?=$v->nf_numero?>" class="btn btn-success btn-block">
                <i class="fa-solid fa-receipt"></i>
                <span><?=(($v->nf_numero)?' N°'.$v->nf_numero:'Nota Fiscal')?></span>
            </button>
        </div>
        <div class="col-2">
            <button
                print_pedido="<?=$v->codigo?>"
                class="btn btn-warning btn-block"
                <?=((!$valor_total)?'disabled':false)?>
            >
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
        <div class="col-5">
            <div class="btn btn-primary btn-block">
                R$ <?=number_format($valor_total, 2, ',', false)?>
            </div>
        </div>
    </div>
</div>


<script>
    $(function(){

        $("button[nota_fiscal]").click(function(){
            venda = $(this).attr("nota_fiscal");
            opc = $(this).attr("opc");
            if(!opc){
                $.confirm({
                    content:"Confirma a emissão da nota fiscal?",
                    title:false,
                    buttons:{
                        'SIM':function(){
                            Carregando();
                            $.ajax({
                                url:"src/produtos/gerar_nota.php",
                                type:"POST",
                                dataType:'JSON',
                                data:{
                                    venda,
                                },
                                success:function(dados){
                                     console.log(dados)
                                    if(dados.status){
                                        $("button[nota_fiscal] span").text(" N°"+dados.nota);
                                        $("button[nota_fiscal]").attr("opc",dados.nota);
                                        $('div[nota="'+venda+'"]').css("display","block");
                                        $('div[acao="'+venda+'"]').removeClass("botao");
                                        $('div[acao="'+venda+'"]').addClass("botaoN");
                                        $("b[numero_nota"+venda+"]").html(dados.nota);
                                        $.alert('Nota gerada com sucesso!');
                                    }else{
                                        $.alert(dados.error);
                                    }
                                    Carregando('none');
                                },
                                error:function(){
                                    $.alert(dados.error);
                                    Carregando('none');
                                }
                            });
                        },
                        'NÃO':function(){

                        }
                    }
                })
            }else{
                $.alert('Sua nota já foi gerada com o N°'+opc);
            }
        });


        $("button[print_pedido]").click(function(){
            venda = $(this).attr("print_pedido");
            if(venda){
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
                                impressora,
                                venda,
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
            }
        });



    })
</script>