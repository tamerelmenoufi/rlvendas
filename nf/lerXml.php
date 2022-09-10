<?php
    include('config.php');

    $query = "select * from vendas where codigo = ?";
    $stmt = $PDO->prepare($query);
    $stmt->execute([10834]);
    $nota = $stmt->fetch(PDO::FETCH_ASSOC);

    $dados = json_decode($nota['nf_json']);

    echo "<pre>";
    // print_r($dados);

    echo $dados->NFe->infNFeSupl->urlChave;
    echo "<br>";
    echo $dados->protNFe->infProt->chNFe;
    echo "<br>";
    echo $dados->protNFe->ide->nNF;
    echo "<br>";
    echo $dados->protNFe->ide->serie;
    echo "<br>";
    echo $dados->protNFe->ide->dhEmi;
    echo "<br>";
    echo $dados->protNFe->infProt->nProt;
    echo "<br>";
    echo $dados->protNFe->infProt->dhRecbto;
    echo "<br>";
    echo $dados->NFe->infNFeSupl->qrCode;

    echo "</pre>";
