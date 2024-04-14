<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['acao'] == 'busca'){
        // busca_tipo,
        // data_inicial,
        // data_final,
        // acao:"busca"
        if($_POST['data_inicial']) $_SESSION['data_inicial'] = $_POST['data_inicial'];
        if($_POST['data_final']) $_SESSION['data_final'] = $_POST['data_final'];
        if($_POST['busca_tipo']) $_SESSION['busca_tipo'] = $_POST['busca_tipo'];

    }

    $where = false;
    if($_SESSION['data_inicial'] > 0){
        $where .= " and data_pedido between '{$_SESSION['data_inicial']} 00:00:00' and '".(($_SESSION['data_final'])?:$_SESSION['data_inicial'])." 23:59:59' ";
    }
    if($_SESSION['busca_tipo']){
        $where .= " and app = '{$_SESSION['busca_tipo']}' ";
    }
    

    $tipo = [
        'aberto' => " and a.deletado != '1' and a.caixa = '0' and a.app = 'mesa' ",
        'paga' => " and a.deletado != '1' and a.caixa != '0' and a.app = 'mesa' and a.situacao = 'pago'",
    ];
?>

<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <div class="input-group">
                <span class="input-group-text">Tipo</span>
                <select id="busca_tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="garcom" <?=(($_SESSION['busca_tipo'] == 'garcom')?'selected':false)?>>Atendimento pelo Garçom</option>
                    <option value="mesa" <?=(($_SESSION['busca_tipo'] == 'mesa')?'selected':false)?>>Pedido feito pelo Cliente (na mesa)</option>
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

<?php
    echo $query = "select a.* from vendas a where a.deletado != '1' {$where} {$tipo[$_GET['tipo']]} limit 30";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

        if($d->app == 'gacom'){
?>
<div class="row">
    <div class="col-md-1"><?=$d->mesa?></div>
    <div class="col-md-2"><?=$d->atendente?></div>
    <div class="col-md-1"><?=$d->valor?></div>
    <div class="col-md-1"><?=$d->desconto?></div>
    <div class="col-md-1"><?=$d->total?></div>
    <div class="col-md-1"><?=$d->situacao?></div>
    <div class="col-md-1"><?=$d->periodo?></div>
</div>
<?php
        }else if($d->app == 'mesa'){
?>
<div class="row">
    <div class="col-md-1"><?=$d->mesa?></div>
    <div class="col-md-2"><?=$d->cliente?></div>
    <div class="col-md-2"><?=$d->atendente?></div>
    <div class="col-md-1"><?=$d->valor?></div>
    <div class="col-md-1"><?=$d->desconto?></div>
    <div class="col-md-1"><?=$d->cupom_valor?></div>
    <div class="col-md-1"><?=$d->total?></div>
    <div class="col-md-1"><?=$d->situacao?></div>
    <div class="col-md-1"><?=$d->periodo?></div>
</div>
<?php
        }else if($d->app == 'delivery'){
?>
<div class="row">
    <div class="col-md-2"><?=$d->cliente?></div>
    <div class="col-md-2"><?=$d->atendente?></div>
    <div class="col-md-1"><?=$d->valor?></div>
    <div class="col-md-1"><?=$d->taxa?></div>
    <div class="col-md-1"><?=$d->desconto?></div>
    <div class="col-md-1"><?=$d->cupom_valor?></div>
    <div class="col-md-1"><?=$d->total?></div>
    <div class="col-md-1"><?=$d->situacao?></div>
    <div class="col-md-1"><?=$d->periodo?></div>
</div>
<?php
        }
?>
    <?=$d->codigo?><br>
<?php
    }
?>

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