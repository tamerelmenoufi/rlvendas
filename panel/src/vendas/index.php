<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    $tipo = [
        'aberto' => " and deletado != '1' and caixa = '0' and app = 'mesa' ",
        'paga' => " and deletado != '1' and caixa != '0' and app = 'mesa' and situacao = 'pago'",
    ];


    $query = "select * from vendas where data_pedido like '".date("Y-m-d")."%' {$tipo[$_POST['tipo']]}";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>

<?php
    }
?>