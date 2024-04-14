<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    $tipo = [
        'aberto' => " and deletado != '1' and caixa = '0' and app = 'mesa' ",
        'paga' => " and deletado != '1' and caixa != '0' and app = 'mesa' and situacao = 'pago'",
    ];
?>

<div class="row">
    <div class="col">
        <div class="input-group mb-3">
            <span class="input-group-text">Tipo</span>
            <select id="busca_tipo">
                <option value="">Todos</option>
                <option value="garcom">Atendimento pelo Garçom</option>
                <option value="mesa">Pedido feito pelo Cliente (na mesa)</option>
                <option value="delivery">Pedido pelo Delivery</option>
            </select>
            <span class="input-group-text">Em</span>
            <input type="date" class="form-control" >
            <span class="input-group-text">até</span>
            <input type="date" class="form-control" >
            <button class="btn btn-outline-secondary" type="button" id="button-addon1">Acahar</button>
        </div>
    </div>
</div>

<?php
    echo $query = "select * from vendas where data_pedido like '".date("Y-m-d")."%' {$tipo[$_GET['tipo']]}";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
    <?=$d->codigo?><br>
<?php
    }
?>

<script>
    $(function(){
        Carregando('none');
    })
</script>