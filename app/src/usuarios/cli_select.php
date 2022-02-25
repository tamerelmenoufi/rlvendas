<?php
  include("../../../../lib/includes.php");

if ($_SERVER['REQUEST_METHOD'] === "GET" and $_GET['select'] === "cidades") {
    $estado = $_GET['codigo'];

    $queryCidades = "SELECT * FROM cidades WHERE cd_estado = '{$estado}'ORDER BY cd_nome";
    $resultCidade = mysql_query($queryCidades);

    echo '<option value=""></option>';
    while ($dCidade = mysql_fetch_object($resultCidade)) { ?>
        <option value="<?= $dCidade->codigo; ?>"><?= utf8_encode($dCidade->cd_nome); ?></option>
    <?php }
}

if ($_SERVER['REQUEST_METHOD'] === "GET" and $_GET['select'] === "bairro") {
    $cidade = $_GET['codigo'];

    $queryBairro = "SELECT * FROM bairros WHERE brs_cidade = '{$cidade}'ORDER BY brs_bairro";
    $resultBairro = mysql_query($queryBairro);

    echo '<option value=""></option>';
    while ($dBairro = mysql_fetch_object($resultBairro)) { ?>
        <option value="<?= $dBairro->codigo; ?>"><?= utf8_encode($dBairro->brs_bairro); ?></option>
    <?php }
}

