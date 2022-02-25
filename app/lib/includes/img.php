<?php
    include("../../../../lib/conexao.php");
    $d = mysql_fetch_object(mysql_query("select * from produtos where codigo = '{$_GET['cod']}'"));

    echo $d->prd_foto;