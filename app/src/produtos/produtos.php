<?php
    include("../../../lib/includes.php");
    $query = "select * from categorias where codigo = '{$_GET['categoria']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $m_q = "select * from categoria_medidas where codigo in({$d->medidas})";
    $m_r = mysqli_query($con, $m_q);
    while($m = mysqli_fetch_array($m_r)){
        $M[$m['codigo']] = $m['medida'];
    }
?>

<style>
    .foto<?=$md5?>{
        background-size:cover;
        background-position:center;
        border-top-left-radius:5px;
        border-bottom-left-radius:5px;
    }
    .topo<?=$md5?>{
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:55px;
        background-color:#fff;
        padding:20px;
        font-weight:bold;
        z-index: 1;
    }
    .IconePedidos{
        position:fixed;
        top:20px;
        right:25px;
        font-size:30px;
        color:green;
        font-weight:bold;
        z-index: 10;
    }
    .MensagemAddProduto{
        position:fixed;
        right:80px;
        top:15px;
        background-color:green;
        color:#fff;
        text-align:center;
        font-weight:bold;
        border-radius:5px;
        padding:5px;
        width:auto;
        z-index:2;
    }
    .MensagemAddProduto span{
        position:absolute;
        right:-5px;
        font-size:40px;
        top:50%;
        margin-top:-10px;
        color:red;
        z-index:3;
    }

</style>

<!-- Informativo de pedidos ativos -->
<i
    class="fa-solid fa-bell-concierge IconePedidos animate__animated animate__tada animate__repeat-3"
></i>

<div class="MensagemAddProduto">
    Produto Adicionado!
    <span><i class="fa-solid fa-caret-right"></i></span>
</div>

<!-- Informativo de pedidos ativos -->


<div class="topo<?=$md5?>">
    <center><?=$d->categoria?></center>
</div>


<div class="col-md-12">
    <?php
        $query = "select * from produtos where categoria = {$d->codigo}";
        $result = mysqli_query($con, $query);
        while($p = mysqli_fetch_object($result)){
            $detalhes = json_decode($p->detalhes);
    ?>
            <div class="card mb-3 item_button<?=$md5?>">
            <div class="row no-gutters">
                <div class="col-4 foto<?=$md5?>" style="background-image:url(../painel/produtos/icon/<?=$p->icon?>)">
                </div>
                <div class="col-8">
                <div class="card-body">
                    <h5 class="card-title"><?=$p->produto?></h5>
                    <p class="card-text"><?=$p->descricao?></p>
                </div>
                </div>
                <div class="card-body">
                <p class="card-text">
                        <small class="text-muted">

                        <?php
                        foreach($detalhes as $i => $val){

                            //echo "<br>R$ {$val[0]} -> Status: R$ {$val[1]}<br>";

                            if($val[1] > 0){
                        ?>
                        <button
                                acao_medida
                                opc="<?=$val[1]?>"
                                produto="<?=$p->codigo?>"
                                titulo='<?="{$d->categoria} - {$p->produto} ({$M[$val[1]]})"?>'
                                categoria='<?=$d->codigo?>'
                                medida='<?=$val[1]?>'
                                valor='<?=$val[0]?>'
                                class="btn btn-outline-success btn-xs"
                                style="height:40px; font-size:11px; line-height: 1.2;"
                        >
                            <?=$M[$val[1]]?><br>
                            R$ <?=number_format($val[0],2,',','.')?>
                        </button>
                        <?php
                            }
                        }
                        ?>

                    </small>
                    </p>
                </div>
            </div>
            </div>


    <?php
        }
    ?>
    </div>
</div>

<script>
    $('.IconePedidos').tooltip('show');
    $("button[acao_medida]").click(function(){
        opc = $(this).attr("opc");
        produto = $(this).attr("produto");
        title = $(this).attr("titulo");
        categoria = $(this).attr("categoria");
        medida = $(this).attr("medida");
        valor = $(this).attr("valor");

        Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:"src/produtos/produto.php",
                    categoria,
                    produto,
                    medida,
                    valor
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });

    });
</script>