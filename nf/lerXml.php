<?php
    include('config.php');

    $query = "select * from vendas where codigo = ?";
    $stmt = $PDO->prepare($query);
    $stmt->execute([10834]);
    $nota = $stmt->fetch(PDO::FETCH_ASSOC);

    $dados = json_decode($nota['nf_json']);

    echo "<pre>";
    //  print_r($dados);

    echo "urlChave: ".$dados->NFe->infNFeSupl->urlChave;
    echo "<br>";
    echo "chNFe: ".$dados->protNFe->infProt->chNFe;
    echo "<br>";


    echo "nNF: ".$dados->NFe->infNFe->ide->nNF;
    echo "<br>";
    echo "serie: ".$dados->NFe->infNFe->ide->serie;
    echo "<br>";
    echo "dhEmi: ".$dados->NFe->infNFe->ide->dhEmi;
    echo "<br>";


    echo "nProt: ".$dados->protNFe->infProt->nProt;
    echo "<br>";
    echo "dhRecbto: ".$dados->protNFe->infProt->dhRecbto;
    echo "<br>";
    echo "qrCode: ".$dados->NFe->infNFeSupl->qrCode;

    echo "</pre>";
