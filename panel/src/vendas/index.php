<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_GET['filtro']){
        $_SESSION['busca_tipo'] = $_GET['filtro'];
    }

    if($_POST['acao'] == 'busca'){
        $_SESSION['data_inicial'] = $_POST['data_inicial'];
        $_SESSION['data_final'] = $_POST['data_final'];
        $_SESSION['busca_tipo'] = $_POST['busca_tipo'];
    }

    $where = false;
    if($_SESSION['data_inicial'] > 0){
        $where .= " and data_pedido between '{$_SESSION['data_inicial']} 00:00:00' and '".(($_SESSION['data_final'])?:$_SESSION['data_inicial'])." 23:59:59' ";
    }
    

    $tipo = [
        'garcom'    => " and a.app = 'garcom' ",
        'cliente'   => " and a.caixa != '0' and a.app = 'mesa' and a.situacao = 'pago'",
        'viagem'    => " and a.app = 'mesa'", // and a.mesa >= 200
        'delivery'  => " and a.app = 'delivery' and a.caixa != '0' and a.situacao = 'pago'",
    ];
?>

<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <div class="input-group">
                <span class="input-group-text">Tipo</span>
                <select id="busca_tipo" class="form-select">
                    <option value="garcom" <?=(($_SESSION['busca_tipo'] == 'garcom')?'selected':false)?>>Atendimento pelo Garçom</option>
                    <option value="cliente" <?=(($_SESSION['busca_tipo'] == 'cliente')?'selected':false)?>>Pedido pelo Cliente (na mesa)</option>
                    <option value="viagem" <?=(($_SESSION['busca_tipo'] == 'viagem')?'selected':false)?>>Pedido para viagem</option>
                    <option value="delivery" <?=(($_SESSION['busca_tipo'] == 'delivery')?'selected':false)?>>Pedido pelo Delivery</option>
                </select>
                <span class="input-group-text">Em</span>
                <input id="data_inicial" value="<?=$_SESSION['data_inicial']?>" type="date" class="form-control" >
                <span class="input-group-text">até</span>
                <input id="data_final" value="<?=$_SESSION['data_final']?>" type="date" class="form-control" >
                <button buscar class="btn btn-outline-secondary" type="button" id="button-addon1">Acahar</button>
            </div>
        </div>
    </div>
</div>


<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <div class="row">
<?php
    $query = "select a.* from vendas a where a.deletado != '1' {$where} {$tipo[$_SESSION['busca_tipo']]} order by a.codigo desc".((!$_SESSION['data_inicial'])?" limit 50 ":false);
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

?>
                <div class="col-md-3 mb-3">
                    <div class="card">
                    <div class="card-header">
                        Pedido #<?=$d->codigo?>
                    </div>
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>Data</span>
                                <span><?=$d->data_pedido?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Mesa</span>
                                <span><?=$d->mesa?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Cliente</span>
                                <span><?=$d->cliente?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Atendente</span>
                                <span><?=$d->atendente?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Valor</span>
                                <span><?=$d->valor?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Taxa</span>
                                <span><?=$d->taxa?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Desconto</span>
                                <span><?=$d->desconto?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Cupom</span>
                                <span><?=$d->cupom_valor?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total</span>
                                <span><?=$d->total?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Situação</span>
                                <span><?=$d->situacao?></span>
                            </div>
                        </li>


                    </ul>
                    </div>
                </div>
<?php
    }
?>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){
        Carregando('none');

        $("button[buscar]").click(function(){

            busca_tipo = $("#busca_tipo").val();
            data_inicial = $("#data_inicial").val();
            data_final = $("#data_final").val();
            Carregando();
            $.ajax({
                url:"src/vendas/index.php",
                type:"POST",
                data:{
                    busca_tipo,
                    data_inicial,
                    data_final,
                    acao:"busca"
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            })

        })
    })
</script>