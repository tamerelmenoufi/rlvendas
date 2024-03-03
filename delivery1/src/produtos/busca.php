<?php
include("../../../lib/includes.php");

VerificarVendaApp('delivery');

function aasort(&$array, $key)
{
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}

?>

<style>
    .foto<?=$md5?> {
        background-size: cover;
        background-position: center;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    .topo<?=$md5?> {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background-color: #fff;
        padding: 20px;
        font-weight: bold;
        z-index: 1;
    }

    .topo<?=$md5?> span {
        position: absolute;
        top: 10px;
        right: 0;
        height:35px;
        width: auto;
        background-color: #a80e13;
        padding: 5px;
        font-weight: bold;
        color:#fff;
        text-align:right;
        z-index: 1;
    }

    .seta<?=$md5?> {
        position: absolute;
        top: -10px;
        left: -17px;
        color:#a80e13;
        font-size:55px;
        z-index: 1;
    }

    .IconePedidos {
        position: fixed;
        top: 10px;
        right: 25px;
        font-size: 30px;
        color: green;
        font-weight: bold;
        z-index: 10;
        display: <?=(($_SESSION['AppCarrinho'])?'block':'none')?>;
    }

    .MensagemAddProduto {
        position: fixed;
        right: 80px;
        top: 15px;
        background-color: green;
        color: #fff;
        text-align: center;
        font-weight: bold;
        border-radius: 5px;
        padding: 5px;
        width: auto;
        z-index: 3;
        display: none;
    }

    .MensagemAddProduto span {
        position: absolute;
        right: -8px;
        font-size: 30px;
        top: -3px;
        color: green;
    }
    .corpo_busca{
        position:fixed;
        left:0;
        right:0;
        top:100px;
        bottom:0;
        overflow:auto;
    }

</style>

<!-- Informativo de pedidos ativos -->

<span class="IconePedidos"><i
            class="fa-solid fa-bell-concierge animate__animated animate__tada animate__repeat-3"
    ></i></span>

<div class="MensagemAddProduto animate__animated animate__shakeX">
    Produto Adicionado!
    <span><i class="fa-solid fa-caret-right"></i></span>
</div>

<!-- Informativo de pedidos ativos -->


<div class="topo<?= $md5 ?>">
    <span><i class="fa-solid fa-caret-left seta<?=$md5?>"></i> Localizar Produtos</span>

    <div class="row" style="position:relative; margin-top:40px;">
        <input type="text" class="form-control filtro" style="padding-right:40px;" />
        <i class="fa-solid fa-magnifying-glass" style="position:absolute; right:10px; top:10px; color:#a1a1a1;"></i>
    </div>
</div>



<div class="col-md-12 corpo_busca" >
    <?php

    $query_c = "select * from categorias where situacao = '1' AND deletado != '1' and codigo <= 7";
    $result_c = mysqli_query($con, $query_c);
    while($d = mysqli_fetch_object($result_c)){


        $query = "select * from produtos where categoria = '{$d->codigo}' AND deletado != '1' order by produto asc";
        $result = mysqli_query($con, $query);

        $m_q = "select * from categoria_medidas where codigo in({$d->medidas}) AND deletado != '1' "
            . "ORDER BY ordem";
        $m_r = mysqli_query($con, $m_q);

        $M = [];

        while ($m = mysqli_fetch_array($m_r)) {
            $M[$m['codigo']] = [
                "ordem" => $m['ordem'],
                "descricao" => $m['medida']
            ];
        }

        while ($p = mysqli_fetch_object($result)) {
            $detalhes = json_decode($p->detalhes, true);
            $detalhes_2 = [];

            foreach ($detalhes as $key => $val) :
                if($val['valor'] > 0 and $val['quantidade'] > 0 ){
                    $val['ordem'] = $M[$key]['ordem'];
                    $detalhes_2[$key] = $val;
                }
            endforeach;
            if($detalhes_2){
            ?>
            <div bloco<?=$p->codigo?> class="card mb-3 mt-3 item_button<?= $md5 ?>">
                <div class="row no-gutters">
                    <div class="col-4 foto<?= $md5 ?>"
                        style="background-image:url(../painel/produtos/icon/<?= $p->icon ?>)">
                    </div>
                    <div class="col-8">
                        <div style="font-size:12px; margin-left:10px; height:100px; position:relative;">
                            <div class="bloco" style="font-size:14px; color:#a80e13; font-weight:bold; overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; text-overflow: ellipsis; direction: ltr;" bloco="<?=$p->codigo?>"><?= $p->produto ?></div>
                            <div style="overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; text-overflow: ellipsis; direction: ltr;"><?= $p->descricao ?></div>
                            <div class="d-flex flex-row bd-highlight" style="margin-top:3px; position:absolute; bottom:3px;">
                            <?php

                            aasort($detalhes_2, "ordem");

                            foreach ($detalhes_2 as $key2 => $val) {
                                if ($val["quantidade"] > 0) {
                                    ?>
                                    <button
                                            acao_medida
                                            opc="<?= $val["quantidade"]; ?>"
                                            produto="<?= $p->codigo ?>"
                                            titulo='<?= "{$d->categoria} - {$p->produto} ({$M[$key2]["descricao"]})" ?>'
                                            categoria='<?= $d->codigo ?>'
                                            medida='<?= $val["quantidade"]; ?>'
                                            valor='<?= $val['valor']; ?>'
                                            class="btn btn-lg"
                                            style="height:auto; background-color:#a80e13; border:0; padding:5px; margin-left:2px; font-size:12px; color:#fff; font-weight:bold; line-height: 1.2;"
                                    >
                                        <?= $M[$key2]['descricao']; ?><br>
                                        R$ <?= number_format($val['valor'], 2, '.', false) ?>
                                    </button>
                                    <?php
                                }
                            }
                            ?>

                            </div>
                        </div>

                    </div>
                </div>
                

            </div>


            <?php
            }
        }
    }
    ?>
</div>

<script>


    $(".filtro").keyup(function(){
        var texto = $(this).val();
        blocos = texto.normalize("NFD").split(' ');
        $(".item_button<?= $md5 ?>").css("display","none");
        tem = 0;
        $(".bloco").each(function(){
           for(i = 0; i < blocos.length; i++ ){

                if(blocos[i].trim()){
                    var resultado = $(this).text().toUpperCase().normalize("NFD").indexOf(blocos[i].toUpperCase());
                    var bloco = $(this).attr("bloco");
                    // console.log(blocos[i])
                    if(resultado < 0) {
                        // $(`div[bloco${bloco}]`).fadeOut();
                    }else {
                        $(`div[bloco${bloco}]`).css("display","block");
                        tem++;
                    }
                }
            }
        });
        if(tem == 0) $(".item_button<?= $md5 ?>").css("display","block");

    });



    $("button[acao_medida]").click(function () {
        opc = $(this).attr("opc");
        produto = $(this).attr("produto");
        title = $(this).attr("titulo");
        categoria = $(this).attr("categoria");
        medida = $(this).attr("medida");
        valor = $(this).attr("valor");

        Carregando();
        $.ajax({
            url: "componentes/ms_popup_100.php",
            type: "POST",
            data: {
                local: "src/produtos/produto.php",
                categoria,
                produto,
                medida,
                valor
            },
            success: function (dados) {
                $(".ms_corpo").append(dados);
            }
        });

    });

    $(".IconePedidos").click(function () {
        $.ajax({
            url: "componentes/ms_popup_100.php",
            type: "POST",
            data: {
                local: "src/produtos/pedido.php",
            },
            success: function (dados) {
                PageClose();
                $(".ms_corpo").append(dados);
            }
        });
    });

</script>