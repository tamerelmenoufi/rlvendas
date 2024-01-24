<?php
    include("../../lib/includes.php");

    $query = "select * from vendas where codigo = '{$_SESSION['AppVenda']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    if($d->operadora_situacao == 'approved'){
        echo "Seu pagamento foi efetuado com sucesso!";
    }else{
        echo "Ocorreu um erro no pagamento, favor conferir os dados do seu cartão ou tente outra operação!";
    }