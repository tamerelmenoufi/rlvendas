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
        'aberto' => " and deletado != '1' and caixa = '0' and app = 'mesa' ",
        'paga' => " and deletado != '1' and caixa != '0' and app = 'mesa' and situacao = 'pago'",
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
    echo $query = "select * from vendas where 1 {$where} {$tipo[$_GET['tipo']]}";
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

        $("button[buscar]").click(function(){

            busca_tipo = $("#busca_tipo").val();
            data_inicial = $("#data_inicial").val();
            data_final = $("#data_final").val();
            Carregando();
            $.ajax({
                url:"src/vendas/index.php",
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