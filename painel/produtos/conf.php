<?php
    //Config
    $ConfTitulo = 'Produtos';
    $UrlScript = 'produtos/';

    if($_GET['categoria']){
        $_SESSION['categoria'] = $_GET['categoria'];
    }
    $ConfCategoria = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));

    //Config ----------
    function getSituacao()
    {
        return [
            '1' => 'Liberado',
            '0' => 'Bloqueado',
        ];
    }