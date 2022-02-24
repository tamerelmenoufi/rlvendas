
<?php
    include("../../lib/includes.php");

    $query = "select a.*, b.categoria as nome_categoria from produtos a left join categorias b on a.categoria = b.codigo where a.codigo = '{$_GET['produto']}'";
    $result = mysqli_query($con, $query);
    $p = mysqli_fetch_object($result);

    $m = mysqli_fetch_object(mysqli_query($con, "select * from categoria_medidas where codigo = '{$_GET['medida']}'"));

?>
<style>
    .cardapio_produto{
        position:absolute;
        left:0;
        top:90px;
        bottom:20px;
        width:100%;
        overflow:auto;
    }
    .fecharJanelaProduto{
        position:absolute;
        right:10px;
        bottom:10px;
    }
    #keyboard{
        background-color:#144766;
    }
    span[valor]{
        margin-left:10px;
    }
    #quantidade{
        text-align:center;
    }
    #rotulo_valor{
        width:180px;
        font-weight:bold;
    }
    .texto_detalhes{
        color:red;
        font-size:12px;
    }
    .foto<?=$md5?>{
        background-size:cover;
        background-position:center;
        border-top-left-radius:5px;
        border-bottom-left-radius:5px;
    }
</style>

<div class="cardapio_produto">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div style="position:fixed; top:55px; left:30px; width:<?=(($m->qt_produtos > 1)?'60%':'calc(100% - 60px)')?>;">
                    <div class="card mb-3">
                        <div class="row">
                            <div class="col-md-4 foto<?=$md5?>" style="background-image:url(../painel/produtos/icon/<?=$p->icon?>)">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?=$p->nome_categoria?> - <?=$p->produto?> (<?=$m->medida?>)</h5>
                                    <p class="card-text"><?=$p->descricao?></p>
                                    <p class="card-text"><small class="text-muted">R$ <?=number_format($_GET['valor'], 2, ',','.')?></small></p>
                                    <p class="card-text">
                                    <div class="input-group input-group-lg mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-danger" type="button" id="menos"><i class="fa-solid fa-circle-minus"></i></button>
                                        </div>
                                        <input type="text" class="form-control" id="quantidade" readonly value="1">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="button" id="mais"><i class="fa-solid fa-circle-plus"></i></button>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="btn btn-primary" id="rotulo_valor">R$ <span valor><?=number_format($_GET['valor'], 2, ',','.')?></span></span>
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
                                <button type="button" class="btn btn-outline-primary btn-block incluir_detalhes">
                                    INCLUIR RECOMENDAÇÕES
                                    <i class="fa-solid fa-hand-pointer"></i>
                                </button>
                            </div>
                    </div>



                    <div style="position:fixed; right:20px; <?=(($m->qt_produtos > 1)?'margin-right:calc(40% - 60px);':false)?> bottom:30px;">
                        <button class="btn btn-success btn-lg btn-block" adicionar_produto opc="add">ADICIONAR</button>
                    </div>
                    <div style="position:fixed; left:20px; bottom:30px;">
                        <button class="btn btn-danger btn-lg btn-block" cancelar_produto opc="del">CANCELAR</button>
                    </div>

                </div>

            </div>
            <div class="col-md-4">
            <?php
            if($m->qt_produtos > 1){
            ?>
            <p style="position:fixed; right:50px; top:55px;"><b>Você pode adicionar mais <?=($m->qt_produtos-1). ' '.(($m->qt_produtos == 2)?'sabor':'sabores')?></b></p>
            <?php
                $query = "select
                                a.*,
                                b.categoria as nome_categoria
                            from produtos a
                            left join categorias b on a.categoria = b.codigo
                        where /*a.categoria = '{$p->categoria}' and*/ a.codigo not in ('{$p->codigo}')";
                $result = mysqli_query($con, $query);
                while($p1 = mysqli_fetch_object($result)){
            ?>
                <div class="list-group" style="margin-bottom:10px;">
                    <a href="#" class="list-group-item list-group-item-action add_sabores"><?=$p1->produto?></a>
                </div>
            <?php
                }
            }
            ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $.ajax({
            url:"cardapio/detalhes.php",
            success:function(dados){
                $("#body").append(dados);
            }
        });

        $.ajax({
            url:"home/header.php",
            success:function(dados){
                $("#body").append(dados);
            }
        });
        $.ajax({
            url:"home/footer.php",
            success:function(dados){
                $("#body").append(dados);
            }
        });

        $("#mais").click(function(){
            quantidade = $("#quantidade").val();
            quantidade = (quantidade*1 + 1);
            $("#quantidade").val(quantidade);
            valor = <?=$_GET['valor']?> * quantidade;
            $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

        });

        $("#menos").click(function(){
            quantidade = $("#quantidade").val();
            quantidade = ((quantidade*1 > 1)?(quantidade*1 - 1):1);
            $("#quantidade").val(quantidade);
            valor = <?=$_GET['valor']?> * quantidade;
            $("span[valor]").html(valor.toLocaleString('pt-br', {minimumFractionDigits: 2}));

        });


        qt = 0;
        $(".add_sabores").click(function(){
            if($(this).is(".active")){
                $(this).removeClass("active");
            }else if(qt < (<?=$m->qt_produtos?> - 1)){
                $(this).addClass("active");
            }
            qt = $(".add_sabores.active").length;
        });

        $(".incluir_detalhes").click(function(){
            $("#keyboard_body").css("display","block");
        });


        $("button[adicionar_produto], button[cancelar_produto]").click(function(){
            categoria = '<?=$p->categoria?>';
            opc = $(this).attr("opc");
            $.ajax({
                url:"cardapio/produtos.php",
                data:{
                    categoria,
                },
                success:function(dados){
                    $("#body").html(dados);
                }
            });
        });

    })
</script>