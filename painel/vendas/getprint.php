<?php

    if(is_file("print/{$_POST['t']}")){
        $dados = file_get_contents("print/{$_POST['t']}");
        echo $dados;
        unlink("print/{$_POST['t']}");
    }

?>