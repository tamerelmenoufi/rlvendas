<?php
    include('config.php');

    $query = "select * from vendas where codigo = ?";
    $stmt = $PDO->prepare($query);
    $stmt->execute([10834]);
    $nota = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $nota['nf_json'];