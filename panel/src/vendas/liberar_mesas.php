<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if ($_POST['acao'] == 'pagar') {
        $mesa = mysqli_fetch_object(mysqli_query($con, "select mesa from vendas where codigo = '{$_POST['cod']}'"));
        if(mysqli_query($con, "update vendas set situacao = 'pago' where codigo = '{$_POST['cod']}'")){
            mysqli_query($con, "UPDATE mesas set blq = '0' WHERE codigo = '{$mesa->mesa}'");
        }
        echo "select mesa from vendas where codigo = '{$_POST['cod']}'";
        exit();
    }
?>
<style>
    .l-100{
        width:100px;
    }
</style>

<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <h1>Liberar Mesas</h1>
            <div class="row">
<?php

    $query = "select * from vendas  where deletado != '1' and mesa != '' and situacao = 'pagar' and app in ('garcom','mesa')";
    $result = mysqli_query($con, $query);
    $ocupadas = [];
    while($d = mysqli_fetch_object($result)){
        $ocupadas[] = $d->mesa;
        $cod_venda[$d->mesa] = $d->codigo;
    }

    $query = "select * from mesas where situacao = '1' and deletado != '1' and CONVERT(mesa, UNSIGNED INTEGER) < 200 order by CONVERT(mesa, UNSIGNED INTEGER) asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

        if($_SESSION['appLogin']->codigo != 1){
?>
                <div class="col-4">
                    <div 
                        <?=((in_array($d->codigo, $ocupadas))?"liberar='{$cod_venda[$d->codigo]}'":false)?> 
                        class="alert alert-<?=((in_array($d->codigo, $ocupadas))?'warning':'secondary')?>" 
                        role="alert"
                        style="position:relative; <?=((in_array($d->codigo, $ocupadas))?'cursor:pointer':false)?>"
                    >
                        <i 
                            class="fa-solid <?=((in_array($d->codigo, $ocupadas))?'fa-lock text-danger':'fa-lock-open text-success')?>"
                            style="position:absolute; right:5px; bottom:5px"
                        ></i>
                        <h1 class="w-100 text-center"><?=str_pad($d->mesa, 3, "0", STR_PAD_LEFT)?></h1>
                    </div>
                </div>
<?php
        }else{
?>
                <div class="col-4">
                    <div class="input-group  input-group-lg m-1">
                        <div 
                            <?=((in_array($d->codigo, $ocupadas))?"liberar='{$cod_venda[$d->codigo]}'":false)?> 
                                class="form-control" 
                                style="position:relative; <?=((in_array($d->codigo, $ocupadas))?'cursor:pointer; background-color:#fff3cd':'background-color:#eee')?>"
                        >                         
                            <i 
                                class="fa-solid <?=((in_array($d->codigo, $ocupadas))?'fa-lock text-danger':'fa-lock-open text-success')?>"
                                style="position:absolute; right:5px; bottom:5px"
                            ></i>                            
                            <h1 class="w-100 text-center"><?=str_pad($d->mesa, 3, "0", STR_PAD_LEFT)?></h1>
                        </div>
                        <button class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-list"></i></button>
                        <button class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-print"></i></button>
                    </div>
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
        Carregando('none');

        $("div[liberar]").click(function () {
            obj = $(this);
            cod = obj.attr("liberar");
            txt = obj.text();
            if(!cod) return false;
            $.confirm({
                title:"Confirmação de pagamento",
                content:`Confirma o pagamento da venda e aliberação da mesa <b>${txt}</B>?`,
                buttons:{
                    sim:{
                        text:"SIM",
                        btnClass:"btn btn-danger btn-sm",
                        action:function(){
                            Carregando()
                            $.ajax({
                                url: "src/vendas/liberar_mesas.php",
                                type: "POST",
                                data: {
                                    cod,
                                    acao:'pagar'
                                },
                                success: function (dados) {
                                    console.log(dados);
                                    obj.removeClass("alert-warning");
                                    obj.addClass("alert-secondary");
                                    obj.removeAttr("liberar");
                                    obj.css("cursor","auto");
                                    obj.children("i").removeClass("fa-lock text-danger");
                                    obj.children("i").addClass("fa-lock-open text-success");
                                    Carregando('none')
                                }
                            });
                        }
                    },
                    nao:{
                        text:"NÃO",
                        btnClass:"btn btn-success btn-sm",
                        action:function(){

                        }
                    }
                }
            })

            
        });
    })
</script>